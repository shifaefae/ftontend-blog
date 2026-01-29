<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = [
            (object) ['id' => 1, 'judul' => 'Judul Blog 1', 'penulis' => 'Penulis 1', 'kategori' => 'Tutorial', 'status' => 'published'],
        ]; // Fetch blog posts from the database or model
        return view('pages.Listblog', compact('blogs'));
    }

    
    public function create()
    {
        return view('pages.Tambahblog');
    }

    public function store(Request $request)
    {
        // Handle the blog post creation logic here
        dd($request->all());
        // return redirect()->route('blog.list');
    }
}
