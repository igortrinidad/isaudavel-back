<?php

namespace App\Http\Controllers;

use App\Models\SiteArticle;
use Illuminate\Http\Request;

class SiteArticleController extends Controller
{
    /**
     * List for Oracle Dashboard
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = SiteArticle::orderBy('created_at', 'DESC')->paginate(12);

        return view('oracle.dashboard.articles.list', compact('articles'));
    }

    /**
     * List for app
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function last_articles_for_app($quantity)
    {
        $articles = SiteArticle::orderBy('created_at', 'DESC')->limit($quantity)->get();

        return response()->json(['articles' => $articles]);
    }

    /**
     * List for app
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function list_random_articles_for_app($quantity)
    {
        $articles = SiteArticle::inRandomOrder()->limit($quantity)->get();

        return response()->json(['articles' => $articles]);
    }

    /**
     * Show for app
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function show_for_app($slug)
    {
        $article = SiteArticle::where('slug', $slug)->first();
        $article->increment('views', 1);

        return response()->json(['article' => $article]);
    }

    /**
     * List for site
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function list_for_site(Request $request)
    {
        $articles = SiteArticle::where('title', 'LIKE', '%' . $request->query('search') . '%')->where('content', 'LIKE', '%' . $request->query('search') . '%')->orderBy('created_at', 'DESC')->paginate(12);

        return view('landing.articles.list', compact('articles'));
    }

    /**
     * Show for site
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function show_for_site($slug)
    {
        $article_fetched = SiteArticle::where('slug', $slug)->first();
        $article_fetched->increment('views', 1);
        $see_too_articles = SiteArticle::inRandomOrder()->limit(6)->get();

        return view('landing.articles.show', compact('article_fetched', 'see_too_articles'));
    }


    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('oracle.dashboard.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->hasFile('file')){
            $file = $request->file('file');

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

            $filePath = 'articles/' . $fileName;

            \Storage::disk('media')->put($filePath, file_get_contents($file), 'public');

            //merge file path on request
            $request->merge(['path' => $filePath, 'filename' => $originalName, 'extension' => $extension]);
            
        }

        $article = SiteArticle::create($request->all());

        return redirect(route('oracle.dashboard.articles.list'));
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $article = SiteArticle::find($id);

        return view('oracle.dashboard.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $article = tap(siteArticle::find($request->get('id')))->update($request->all())->fresh();

        if($request->hasFile('file')){

            \Storage::disk('media')->delete($article->path);
            
            $file = $request->file('file');

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

            $filePath = 'articles/' . $fileName;

            \Storage::disk('media')->put($filePath, file_get_contents($file), 'public');

            //merge file path on request
            $request->merge(['path' => $filePath, 'filename' => $originalName, 'extension' => $extension]);
            
        }

        return redirect(route('oracle.dashboard.articles.list'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroyed = Exam::destroy($id);

        return redirect(route('oracle.dashboard.articles.list'));

    }

}
