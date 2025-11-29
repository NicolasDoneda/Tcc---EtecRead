<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    /**
     * Lista todas as reservas
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'book.category']);

        // Filtro por status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reservations = $query->get();

        return response()->json([
            'success' => true,
            'count' => $reservations->count(),
            'data' => $reservations->map(function($reservation) {
                return [
                    'id' => $reservation->id,
                    'student' => [
                        'id' => $reservation->user->id,
                        'name' => $reservation->user->name,
                        'rm' => $reservation->user->rm,
                        'ano_escolar' => $reservation->user->ano_escolar,
                        'photo_url' => $reservation->user->photo_url,
                    ],
                    'book' => [
                        'id' => $reservation->book->id,
                        'title' => $reservation->book->title,
                        'cover_image' => $reservation->book->cover_image ? asset('storage/' . $reservation->book->cover_image) : null,
                        'category' => $reservation->book->category->name,
                        'available_quantity' => $reservation->book->available_quantity,
                    ],
                    'reserved_at' => $reservation->reserved_at,
                    'status' => $reservation->status,
                    'created_at' => $reservation->created_at,
                ];
            })
        ], 200);
    }

    /**
     * Estatísticas de reservas
     */
    public function statistics()
    {
        $total = Reservation::count();
        $pending = Reservation::where('status', 'pendente')->count();
        $confirmed = Reservation::where('status', 'confirmado')->count();
        $cancelled = Reservation::where('status', 'cancelado')->count();

        // Reservas por status
        $byStatus = Reservation::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                return [
                    'status' => $item->status,
                    'count' => $item->count,
                ];
            });

        // Livros mais reservados
        $topReservedBooks = Reservation::with('book')
            ->selectRaw('book_id, COUNT(*) as count')
            ->groupBy('book_id')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'book_id' => $item->book->id,
                    'book_title' => $item->book->title,
                    'cover_image' => $item->book->cover_image ? asset('storage/' . $item->book->cover_image) : null,
                    'reservations_count' => $item->count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'pending' => $pending,
                'confirmed' => $confirmed,
                'cancelled' => $cancelled,
                'by_status' => $byStatus,
                'top_reserved_books' => $topReservedBooks,
            ]
        ], 200);
    }

    /**
     * Detalhes de uma reserva específica
     */
    public function show($id)
    {
        $reservation = Reservation::with(['user', 'book.category'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $reservation->id,
                'student' => [
                    'id' => $reservation->user->id,
                    'name' => $reservation->user->name,
                    'email' => $reservation->user->email,
                    'rm' => $reservation->user->rm,
                    'ano_escolar' => $reservation->user->ano_escolar,
                    'photo_url' => $reservation->user->photo_url,
                ],
                'book' => [
                    'id' => $reservation->book->id,
                    'title' => $reservation->book->title,
                    'isbn' => $reservation->book->isbn,
                    'cover_image' => $reservation->book->cover_image ? asset('storage/' . $reservation->book->cover_image) : null,
                    'category' => $reservation->book->category->name,
                    'available_quantity' => $reservation->book->available_quantity,
                ],
                'reserved_at' => $reservation->reserved_at,
                'status' => $reservation->status,
                'created_at' => $reservation->created_at,
                'updated_at' => $reservation->updated_at,
            ]
        ], 200);
    }
}