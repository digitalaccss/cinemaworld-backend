<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog;
use App\Models\BlogType;

class BlogController extends Controller
{
    public function getAllBlogs(Request $req){
        $blogModel = (new Blog)->newQuery();

        if($req->has('type')){
            switch($req->type){
                case 'blog':
                    $blogModel->where('blog_type_id', 1);
                    break;
                case 'partnership':
                    $blogModel->where('blog_type_id', 2);
                    break;
                case 'events':
                    $blogModel->where('blog_type_id', 3);
                    break;
                case 'archive':
                    $blogModel->where('blog_type_id', 4);
                    break;
                case 'journal':
                    $blogModel->where('blog_type_id', 5);
                    break;
                
                default:
                    return response()->json('This blog filter type is invalid', 202);
            }
        }

        $blogs = $blogModel->select('id', 'title', 'slug', 'blog_type_id', 'cover_photo_path', 'cover_photo_alt', 'created_at')->orderBy('created_at', 'desc')->get();

        if(count($blogs) >= 1){
            $data = [];

            foreach($blogs as $indexPos => $blog){
                $data[$indexPos]['id'] = $blog->id;
                $data[$indexPos]['title'] = $blog->title;
                $data[$indexPos]['slug'] = '/blogs/'.$blog->slug;
                $data[$indexPos]['blog_type'] = BlogType::where('id', $blog->blog_type_id)->value('blog_type');
                if($blog->cover_photo_path){
                    if(Storage::disk('public')->exists($blog->cover_photo_path)){
                        $blog->cover_photo_path = Storage::url($blog->cover_photo_path);
                    }
                    else {
                        $blog->cover_photo_path = null;
                    }
                    $data[$indexPos]['cover_photo_path'] = $blog->cover_photo_path;
                    $data[$indexPos]['cover_photo_alt'] = $blog->cover_photo_alt;
                }

                $data[$indexPos]['published_at'] = date('d M Y', strtotime($blog->created_at));
                $data[$indexPos]['created_at'] = $blog->created_at->toIso8601String();
            }

            return response()->json($data, 200);
        }
        else {
            //return response()->json('No blogs were found that matches your filter', 202);
            return response()->json('No blogs were found that matches your filter', 202);
        }
    }

    public function getBlog(Request $req){
        $blog = Blog::select('id', 'title', 'slogan', 'slug', 'blog_type_id', 'content', 'facebook_link_url', 'banner_path', 'banner_alt', 'created_at', 'description', 'meta_title', 'meta_description')
        ->where('slug', $req->slug)
        ->first();

        if($blog){
            $data = [];

            $data['id'] = $blog->id;
            $data['title'] = $blog->title;
            $data['slogan'] = $blog->slogan;
            $data['slug'] = '/blogs/'.$blog->slug;
            $data['blog_type'] = BlogType::where('id', $blog->blog_type_id)->value('blog_type');
            $data['content'] = $blog->content;
            $data['facebook_link_url'] = $blog->facebook_link_url;
            $data['description'] = $blog->description;
            $data['meta_title'] = $blog->meta_title;
            $data['meta_description'] = $blog->meta_description;
            $data['banner_alt'] = $blog->banner_alt;
            if($blog->banner_path){
                if(Storage::disk('public')->exists($blog->banner_path)){
                    $blog->banner_path = Storage::url($blog->banner_path);
                }
                else {
                    $blog->banner_path = null;
                }
                $data['banner_path'] = $blog->banner_path;
            }

            $timestamp = strtotime($blog->created_at);

            $data['published_at_date'] = date('d M Y', $timestamp);
            $data['published_at_time'] = date('H:i a', $timestamp);

            return response()->json($data, 200);
        }
        else {
            return response()->json('This blog was not found', 202);
        }
    }
}
