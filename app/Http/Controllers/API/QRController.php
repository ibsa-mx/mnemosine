<?php

namespace Mnemosine\Http\Controllers\API;

use Illuminate\Http\Request;
use Mnemosine\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Mnemosine\User;
use Mnemosine\Piece;
use Mnemosine\Photography;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class QRController extends Controller
{
    public $tokenValidHours = 2;

    public function index(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->input("user"), 'password' => $request->input('password')])) {
                $token = Str::random(60);
                $user = Auth::user();
                if(!$user->can('ver_qr')){
                    return $this->noPermission();
                }

                $userModel = User::findOrFail($user->id);
                $userModel->api_token = hash('sha256', $token);
                $userModel->tokenized_at = now();
                $userModel->save();

                $response = [
                    'object' => [
                        'token' => $token,
                        'nombre' => $user->name,
                    ],
                    'message' => 'Bienvenido',
                    'success' => 1,
                    'version' => 1
                ];
            } else{
                $response = [
                    'message' => 'La contraseña o el usuario son incorrectos',
                    'success' => 0
                ];
            }
            return response()->json($response);
        } catch (Exception $ex) {
            abort(500, 'Ocurrió un error, no es posible validar al usuario');
        }
    }

    public function show(Request $request)
    {
        try {
            $user = Auth::user();

            if(!$user->can('ver_qr')){
                return $this->noPermission();
            }
            $this->tokenStillIsValid($user);

            $piece = Piece::with([
                'photography',
                'research',
                'location:id,name'
            ])->where('inventory_number', $request->idPieza)->first();

            // se manda un error en caso de que no exista el numero de inventario
            if(is_null($piece)){
                return $this->noData();
            }

            $fieldNames = [
                'inventory_number' => 'No. inventario',
                'origin_number' => 'No. procedencia',
                'catalog_number' => 'No. catálogo',
                'research.title' => 'Título',
                'description_origin' => 'Descripción',
                'research.technique' => 'Técnica',
                'research.period.title' => 'Época',
                'research.place_of_creation.title' => 'Procedencia',
                'location.name' => 'Ubicación'
            ];

            $datos = array();
            foreach ($fieldNames as $key => $fieldName) {
                $vars = explode(".", $key);
                switch(count($vars)){
                    case 3:
                        $valor = $piece[$vars[0]][$vars[1]][$vars[2]];
                        break;
                    case 2:
                        $valor = $piece[$vars[0]][$vars[1]];
                        break;
                    default:
                        $valor = $piece[$vars[0]];
                }
                $datos[] = [
                    'titulo' => $fieldName,
                    'mensaje' => $valor
                ];
            }
            $token = $request->bearerToken();
            $imagenes = array();
            foreach ($piece->photography->sortBy('module_id') as $key => $photography) {
                $imagenes[] = [
                    'imagenesUrl' => route('qr.image', [$photography->module_id, 'original', $photography->file_name]) . '?api_token=' . $token,
                    'imagenesUrlMini' => route('qr.image', [$photography->module_id, 'mini', $photography->file_name]) . '?api_token=' . $token,
                ];
            }
            $response = [
                'object' => [
                    'datos' => $datos,
                    'imagenes' => $imagenes
                ],
                'message' => 'Ok',
                'success' => 1,
                'version' => 1
            ];
            return response()->json($response);
        } catch (Exception $ex) {
            abort(500, 'Ocurrió un error, no es posible obtener información de la pieza');
        }
    }

    public function image(Request $request, $module, $size, $fileName)
    {
        try {
            $user = Auth::user();
            if(!$user->can('ver_qr')){
                return $this->noPermission();
            }
            $this->tokenStillIsValid($user);

            $photography = Photography::where([
                'module_id' => $module,
                'file_name' => $fileName
            ])->first();

            // se manda un error en caso de que no exista el numero de inventario
            if(is_null($photography)){
                return $this->noData();
            }

            $modules = [
                1 => 'inventory',
                2 => 'research',
                3 => 'restoration',
            ];

            switch($size){
                case 'mini':
                    $filePath = public_path('storage') . '/' . config('fileuploads.'. $modules[$module] .'.photographs.thumbnails') . '/' . $photography->file_name;
                    break;
                case 'original':
                    $filePath = public_path('storage') . '/' . config('fileuploads.'. $modules[$module] .'.photographs.originals') . '/' . $photography->file_name;
                    break;
                default:
                    return $this->noData();
            }

            if(!file_exists($filePath)){
                return $this->noData();
            }

            $fileContent = file_get_contents($filePath);

            return response($fileContent)
                ->withHeaders([
                    'Content-Type' => $photography->mime_type,
                    'Content-Disposition' => 'inline'
                ]);
        } catch (Exception $ex) {
            abort(500, 'Ocurrió un error, no es posible obtener información de la pieza');
        }
    }

    public function noData()
    {
        $response = [
            'message' => 'Consulta sin datos',
            'success' => 0
        ];
        return response()->json($response);
    }

    public function noPermission()
    {
        $response = [
            'message' => 'No tiene permiso para usar este servicio',
            'success' => 0
        ];
        return response()->json($response, 403);
    }

    public function tokenStillIsValid($user)
    {
        try{
            $tokenTimeIsValid = Carbon::now()->subHours($this->tokenValidHours)->timestamp;
            if($user->tokenized_at->timestamp < $tokenTimeIsValid){
                // ya no es valido
                abort(401, 'Ha expirado el token, vuelva a identificarse.');
            }

            // se renueva la validez del token al momento actual de la peticion
            $userModel = User::findOrFail($user->id);
            $userModel->tokenized_at = now();
            $userModel->save();
        } catch (Exception $ex) {
            abort(500, 'Ocurrió un error, no es posible obtener información de la pieza');
        }
    }
}
