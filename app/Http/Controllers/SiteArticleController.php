<?php

namespace App\Http\Controllers;

use App\Models\SiteArticle;
use Illuminate\Http\Request;

class SiteArticleController extends Controller
{
    /**
     * Display a listing of the resource.
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
