<?php

namespace App\Http\Controllers;

use App\Models\Peticione;
use App\Models\User;
use App\Models\categoria;
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
            $peticiones = Peticione::all();
            return $peticiones;
        }catch (\Exception $exception){
           return response()->json(['error'=>$exception->getMessage()]);
        }

    }

    public function listMine(){

        try{
             //parent::index()
            //$user = Auth::user();
            $id=1;
            $peticiones= Peticione::all()->where('user_id',$id);
            return $peticiones;
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }


    public function show(Request $request, $id)
    {
        try{
            $peticion = Peticione::all()->where('user_id',$id);
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
            if($request -> user()->cannot('update', Auth::user(), $peticion)){
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
            ]);

            $input = $request->all();
            $category = Categoria::findOrFail($request->input('categoria_id'));
            $user = 1;

            $peticion = new Peticione($input);
            $peticion->user()->associate($user);
            $peticion->categoria()->associate($category);
            $peticion->firmantes = 0;
            $peticion->estado = 'pendiente';
            $peticion->save();

            return $peticion;
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }

    }
    public function firmar(Request $request, $id)
    {
        try{
            $peticion = Peticione::findOrFail($id);
            //$user = Auth::user();
            $user = 1;
            $user_id = [$user];
            //$user_id = [$user‐>id];
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
            if($request -> user()->cannot('delete',Auth::user(), $peticion)){
                return response()->json(['error'=>'No autorizado'], 403);
            }
            $peticion->delete();
            return $peticion;
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }

}
