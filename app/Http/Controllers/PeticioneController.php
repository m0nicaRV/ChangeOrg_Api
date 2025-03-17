<?php

namespace App\Http\Controllers;

use App\Models\Peticione;
use App\Models\User;
use App\Models\categoria;
use App\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;




class PeticioneController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $peticiones = Peticione::with('file','user','categoria')->get();
            return $peticiones;
        }catch (\Exception $exception){
           return response()->json(['error'=>$exception->getMessage()]);
        }

    }

    public function listmine(){

        try{
            $user = Auth::user();
            //$id=1;
            $peticiones = Peticione::with('file', 'user', 'categoria')->where('user_id', $user->id)->get(); 
            return $peticiones;
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }


    public function show(Request $request, $id)
    {
        try{
            $peticion = Peticione::with('file', 'user', 'categoria')->where('id',$id)->get();
            return $peticion;
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            $peticion = Peticione::findOrFail($id);
            if($request -> user()->cannot('update', $peticion)){
                return response()->json(['error'=>'No autorizado'], 403);
            }
            $peticion->update($request->all());
            return $peticion;
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'titulo' => 'required|max:255',
                'descripcion' => 'required',
                'destinatario' => 'required',
                'categoria_id' => 'required',
                'file'=>'required'
            ]);

            $input = $request->all();
            $category = Categoria::findOrFail($request->input('categoria_id'));
            $user = Auth::user();

            $peticion = new Peticione($input);
            $peticion->user()->associate($user);
            $peticion->categoria()->associate($category);
            $peticion->firmantes = 0;
            $peticion->estado = 'pendiente';
            $res = $peticion->save();

            if($res){
                $res_file=$this->fileUpload($request,$peticion->id);
                if($res_file){
                    return $peticion;
                }
                return back()->withErrors('Error creando peticion')->withInput();
            }

            return $peticion;
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }

    }

    public function fileUpload(Request $req, $peticione_id = null){
        $file = $req->file('file');
        $fileModel = new File;
        $fileModel->peticione_id = $peticione_id;
        if ($req->file('file')) {
            $filename = $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move('storage/', $filename);
            $fileModel->name = $filename;
            $fileModel->file_path = $filename;
            $res = $fileModel->save();
            return $fileModel;
            if ($res) {
                return 0;
            } else {
                return 1;
            }
        }
        return 1;
    }
    public function firmar(Request $request, $id)
    {
        try{
            $peticion = Peticione::findOrFail($id);
            $user = Auth::user();
            $user_id = [$user->id];
            if($request->user()->cannot('firmar',$peticion )){
                return response()->json(['error'=>'Ya has firmado esta peticion'], 403);
            }
            //$user = 1;
            //$user_id = [$user];

            $peticion->firmas()->attach($user_id);
            $peticion->firmantes = $peticion->firmantes + 1;
            $peticion->save();
            return $peticion;
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }
    public function cambiarEstado(Request $request, $id)
    {
        try{
            $peticion = Peticione::findOrFail($id);
            if($request -> user()->cannot('cambiarEstado', $peticion)){
                return response()->json(['error'=>'No autorizado'], 403);
            }
            $peticion->estado = 'aceptada';
            $peticion->save();
            return $peticion;
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }
    public function delete(Request $request, $id)
    {
        try{
            $peticion = Peticione::findOrFail($id);
            if($request -> user()->cannot('delete', $peticion)){
                return response()->json(['error'=>'No autorizado'], 403);
            }
            $peticion->delete();
            return $peticion;
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }

}
