<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $certifications = Certification::where('professional_id', $id)->with(['professional'])->orderBy('priority', 'DESC')->get();

        return response()->json(['certifications' => $certifications]);
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

            $filePath = 'certifications/' . $fileName;

            \Storage::disk('media')->put($filePath, file_get_contents($file), 'public');

            //merge file path on request
            $request->merge(['path' => $filePath, 'filename' => $originalName, 'extension' => $extension]);
            
        }

            

        $certification = Certification::create($request->all());

        return response()->json([
            'message' => 'Certification created.',
            'certification' => $certification->fresh()
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $certification = Certification::find($id);

        return response()->json(['certification' => $certification]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        if($request->hasFile('file')){
            $file = $request->file('file');

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

            $filePath = 'certifications/' . $fileName;

            \Storage::disk('media')->put($filePath, file_get_contents($file), 'public');

            //merge file path on request
            $request->merge(['path' => $filePath, 'filename' => $originalName, 'extension' => $extension]);
        }
            
        $certification = tap(Certification::find($request->get('id')))->update($request->all())->fresh();

        return response()->json([
            'message' => 'Certification updated.',
            'certification' => $certification
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroyed = Certification::destroy($id);

        if($destroyed){
            return response()->json([
                'message' => 'Certification destroyed.',
                'id' => $id
            ]);
        }

        return response()->json([
            'message' => 'Certification not found.',
        ], 404);

    }

}
