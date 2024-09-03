<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Cache;


class BookService
{
    public function listAllBooks(array $filters,int $perPage)
    {
        // Generate a unique cache key based on filters and pagination
        $cacheKey = 'books_' . md5(json_encode($filters) . $perPage . request('page', 1));

        // Check if the cached result exists
        $books = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters,$perPage)
         {
            // Initialize the query builder for the book model
            $booksQuery = Book::query();

            // Apply author filter if provided
            if (isset($filters['author'])) {
                $booksQuery->where('author', $filters['author']);
            }

            // Apply category_id filter if provided
            if (isset($filters['category_id'])) {
                $booksQuery->whereDoesntHave('borrows', function ($query) {
                    $query->wherenotNull('returned_at');
                })
                ->where('category_id',$filters['category_id']);
            }
            if(isset($filters['available']))
            {
                $booksQuery->whereDoesntHave('borrows', function ($query) {
                    $query->wherenotNull('returned_at');
                });

            }




            $booksQuery->join('categories', 'books.category_id', '=', 'categories.id')
            ->select([
                'books.title',
                'books.author',
                'books.description',
                'books.published_at',
                'books.category_id',
                'categories.name as category_name'
            ])->withAvg('ratings','rating');





            // Return the paginated result of the query
            return $booksQuery->paginate($perPage);
        });

        return $books;
    }
    public function createBook(array $data)
    {
        DB::beginTransaction();
        try
        {
           $book = Book::create($data);
            DB::commit();
            return $book;
        }catch(Exception $e)
        {
            DB::rollBack();
            throw $e;
        }

    }

    public function getBook(Book $book)
    {
        // $book = Book::findOrFail($id);
        return $book;
    }

    public function updateBook(array $data,Book $book)
    {
        // $book = Book::findOrFail($id);
        $book->update(array_filter($data));
        return $book;
    }

    public function deleteBook(Book $book)
    {
        // $book = Book::findOrFail($id);
        $book->forcedelete();
    }
}
