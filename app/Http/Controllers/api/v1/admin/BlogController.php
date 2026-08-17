<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\blogs\addBlogRequest;
use App\Http\Requests\api\v1\admin\blogs\changeStatusRequest;
use App\Http\Requests\api\v1\admin\blogs\getBlogRequest;
use App\Http\Requests\api\v1\admin\blogs\updateBlogRequest;
use App\Http\Resources\api\v1\admin\blogs\getBlogResource;
use App\Models\Blog;
use App\Services\ImageService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

#[Group('Admin · Blogs', weight: 0)]
class BlogController extends Controller
{
    public function index(getBlogRequest $request)
    {
        $blogs = Blog::query()

            ->when($request->boolean('is_mine'), function ($query) {
                $query->where('created_by', auth()->id());
            })

            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })

            ->latest()
            ->get();

        return $this->success(
            message: 'Blogs fetched successfully',
            data: getBlogResource::collection($blogs),
        );
    }

    public function show($blogId)
    {
        $blog = Blog::find($blogId);

        if (!$blog) {
            return $this->notFound(message: 'Blog not found');
        }

        return $this->success(
            message: 'Blog fetched successfully',
            data: getBlogResource::make($blog)
        );
    }

    public function store(addBlogRequest $request)
    {
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title) . '-' . time();
        $blog->detail = $request->detail;
        $blog->status = $request->status;
        $blog->created_by = auth()->user()->id;

        if ($request->hasFile('image')) {
            $blog->image = ImageService::addImage('images/blogs', $request->file('image'), 'blog_');
        }

        if ($request->status === 'published') {
            $blog->published_at = now();
        }

        $blog->save();

        return $this->success(
            message: 'Blog created successfully',
            data: null
        );
    }

    public function update(updateBlogRequest $request, $blogId)
    {
        $blog = Blog::find($blogId);

        if (!$blog) {
            return $this->notFound(message: 'Blog not found');
        }

        $blog->title = $request->title ?? $blog->title;
        $blog->detail = $request->detaile ?? $blog->detaile;
        $blog->status = $request->status ?? $blog->status;
        if ($request->filled('title')) {
            $blog->slug = Str::slug($request->title);
        }
        // Handle image upload
        if ($request->hasFile('image')) {
            $blog->image = ImageService::updateImage('images/blogs/',$request->image, $blog->image, 'blog_');
        }

        if ($request->status === 'published') {
            $blog->published_at = now();
        }

        $blog->save();

        return $this->success(
            message: 'Blog updated successfully',
            data: null
        );
    }

    public function destroy($blogId)
    {
        $blog = Blog::find($blogId);

        if (!$blog) {
            return $this->notFound(message: 'Blog not found');
        }

        $blog->delete();

        return $this->success(
            message: 'Blog deleted successfully'
        );
    }

    public function changeStatus(changeStatusRequest $request)
    {
        $blog = Blog::find($request->blog_id);
        $blog->status = $request->status;
        $blog->save();

        return $this->success(
            message: 'Blog status updated successfully'
        );
    }


}
