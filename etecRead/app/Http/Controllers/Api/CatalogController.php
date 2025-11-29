<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    private function getImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        $filename = basename($imagePath);
        return url('storage/books/' . $filename);
    }

    public function index(Request $request)
    {
        $query = Book::with(['category', 'authors']);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('available_only') && $request->available_only === 'true') {
            $query->where('available_quantity', '>', 0);
        }

        $books = $query->get();

        return response()->json([
            'success' => true,
            'data' => $books->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'isbn' => $book->isbn,
                    'publisher' => $book->publisher,
                    'publication_year' => $book->publication_year,
                    'total_quantity' => $book->total_quantity,
                    'available_quantity' => $book->available_quantity,
                    'cover_image' => $this->getImageUrl($book->cover_image),
                    'category' => [
                        'id' => $book->category->id,
                        'name' => $book->category->name,
                    ],
                    'authors_names' => $book->authors->pluck('name')->join(', '),
                    'authors' => $book->authors->map(function($author) {
                        return [
                            'id' => $author->id,
                            'name' => $author->name,
                        ];
                    }),
                ];
            })
        ]);
    }

    public function show($id)
    {
        $book = Book::with(['category', 'authors'])->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'isbn' => $book->isbn,
                'publisher' => $book->publisher,
                'publication_year' => $book->publication_year,
                'total_quantity' => $book->total_quantity,
                'available_quantity' => $book->available_quantity,
                'cover_image' => $this->getImageUrl($book->cover_image),
                'category' => [
                    'id' => $book->category->id,
                    'name' => $book->category->name,
                ],
                'authors' => $book->authors->map(function($author) {
                    return [
                        'id' => $author->id,
                        'name' => $author->name,
                    ];
                }),
            ]
        ]);
    }

    public function statistics()
    {
        $totalBooks = Book::count();
        $availableBooks = Book::where('available_quantity', '>', 0)->count();
        $totalCategories = Category::count();
        $totalAuthors = Author::count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_books' => $totalBooks,
                'available_books' => $availableBooks,
                'total_categories' => $totalCategories,
                'total_authors' => $totalAuthors,
            ]
        ]);
    }

    public function categories()
    {
        $categories = Category::withCount('books')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function authors()
    {
        $authors = Author::withCount('books')->get();

        return response()->json([
            'success' => true,
            'data' => $authors
        ]);
    }

    public function showAuthor($id)
    {
        $author = Author::with('books')->find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Autor não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $author->id,
                'name' => $author->name,
                'books' => $author->books->map(function($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'cover_image' => $this->getImageUrl($book->cover_image),
                        'available_quantity' => $book->available_quantity,
                    ];
                })
            ]
        ]);
    }

    public function advancedSearch(Request $request)
    {
        $query = Book::with(['category', 'authors']);

        if ($request->has('query') && $request->input('query')) {
            $searchTerm = $request->input('query');
            $filter = $request->input('filter', 'title');

            switch ($filter) {
                case 'title':
                    $query->where('title', 'LIKE', "%{$searchTerm}%");
                    break;
                case 'isbn':
                    $query->where('isbn', 'LIKE', "%{$searchTerm}%");
                    break;
                case 'author':
                    $query->whereHas('authors', function($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%");
                    });
                    break;
                case 'publisher':
                    $query->where('publisher', 'LIKE', "%{$searchTerm}%");
                    break;
            }
        }

        if ($request->has('category_id') && $request->input('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('year') && $request->input('year')) {
            $query->where('publication_year', $request->input('year'));
        }

        if ($request->has('available_only') && $request->input('available_only') === 'true') {
            $query->where('available_quantity', '>', 0);
        }

        $books = $query->get();

        return response()->json([
            'success' => true,
            'data' => $books->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'isbn' => $book->isbn,
                    'publisher' => $book->publisher,
                    'publication_year' => $book->publication_year,
                    'available_quantity' => $book->available_quantity,
                    'cover_image' => $this->getImageUrl($book->cover_image),
                    'category' => [
                        'id' => $book->category->id,
                        'name' => $book->category->name,
                    ],
                    'authors_names' => $book->authors->pluck('name')->join(', '),
                ];
            })
        ]);
    }
}