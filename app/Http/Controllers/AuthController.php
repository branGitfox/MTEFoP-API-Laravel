<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Mail\SendMail;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Inscrire un nouveau utilisateur
     */
    public function register(Request $request){
        
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['unique:users', 'email', 'required'],
            'im' => ['required','unique:users'],
            'id_dir' => ['required'],
            'id_serv' => ['required', 'exists:servs,s_id'],
            'role' => 'required',
            'status' => 'required',
            'password' => ['required', 'confirmed', 'min:8']
        ],
    
    [
        'name.required' => 'Le champ nom est requis',
        'id_serv.required' => 'Le champ service est requis',
        'id_serv.exists' => 'Le service selectionne n\'existe pas',
        'email.required' => 'Le champ email est requis',
        'id_dir.required' => 'Le champ direction est requis',
        'password.required' => 'Le champ mot de passe est requis',
        'role.required' => 'Le champ role est requis',
        'im.required' => 'Le champ imatricule est requis',
        'email.unique' => 'Cet email est deja pris',
        'email.email' => 'Cet email n\'est pas valide',
        'im.unique' => 'Cet imatricule est deja pris',
        'password.min' => 'Le mot de passe doit etre au minimum 8 caracteres',
        'password.confirmed' => 'Les mots de passes ne correspondent pas'
       
    ]);
        $user =User::create($fields);
        Mail::to($user->email)->send(new SendMail([
                    'name' => 'Votre mot de passe e-parapheur est '.$request->password
        ]));
        
        return [
            'message' => 'utilisateur creer avec success',
           
        ];
        
    }

    /**
     * Connecte un utilisateur
     */
    public function login(Request $request) {

        $request->validate(
            [
            'email' => 'required|exists:users|email',
            'password'=> 'required'
            ],
            [
                'email.required'=>'Le champ email est requis',
                'email.exists' => 'Ce email n\'existe pas',
                'email.email' => 'Ce email n\'est pas valide',
                'password.required' => 'le champ mot de passe est requis'
            ]
    );

       $user = User::where('email', $request->email)->join('dirs', 'dirs.d_id', '=', 'users.id_dir')->join('servs', 'servs.s_id', '=', 'users.id_serv')->first();
       if($user && Hash::check($request->password, $user->password)){
          

            if($user->status !== 'desactive'){
                $token = $user->createToken($user->name);
                return [
                    'user' => $user,
                    'token' => $token->plainTextToken
                ];
            }else{
                throw new Error('Votre compte est Bloque, veuillez informer l\'administrateur');
            }
       }

       

       return throw new Error('email ou mot de passe incorrect');
    }

    /**
     * Deconnecte un utilisateur(connectee)
     */
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return [
            'message' => 'utilisateur deconnecte'
        ];
    }

    /**
     * Modifie le status d'un compte d'un utilisateur
     */
    public function updateStatus(Request $request){
        $user_id = $request->route('user_id');
       $user = User::where('id', $user_id)->first();
       if($user->status == 'active'){
        $user->status = 'desactive';
       }else{
        $user->status = 'active';
       }

    $user->update(['status' => $user->status]);
    return $user;
    }

    /**
     * Modification des informations d'un compte utilisateur (mot de passe)
     */

    public function updateUserPassword(Request $request){
      $request->validate([
            'password' => 'required',
            'new_password' => 'required|confirmed|min:8'
        ], [
            'password.required' => 'Le champ mot de passe est requis',
            'new_password.required' => 'Le champ mot de passe est requis',
            'new_password.confirmed' => 'Les mots de passes ne se correspondent pas',
            'new_password.min' => 'Le mot de passe doit etre au moins 8 caracteres'
        ],
    
    );

        if(Hash::check($request->password, $request->user()->password)){
            $request->user()->update(['password' => $request->new_password]);
            return [
                'message' => 'Votre mot de passe a ete modifiee avec succes'
            ];
        }else{
            throw new Error('Votre mot de passe est incorrect');
        }


    }

        /**
     * Modification des informations d'un compte utilisateur
     */

     public function updateUserInfo(Request $request){
        $fields = $request->validate([
              'name' => 'required',
            //   'new_im' => '',
          ], 
        
        ['name.required' => 'Le champ nom est obligatoire']);
  
          $request->user()->update($fields);
          return [
            'message' => 'Votre nom a ete modifiee avec succes'
          ];
      }


      /**
       * Mot de passe oublie
       * 
       */

       public function forgotPassword(Request $request) {
           $email = $request->validate([
                'email' => 'required|email|exists:users'
            ]);

            $user = User::where('email', $email)->first();
            if($user){
                $token = $user->createToken($request->email);
                Mail::to($request->email)->send(new ResetPassword([
                    'name' => env('APP_URL').":8000/api/resetPassword/$request->email/".$token->plainTextToken
                ]));

                return [
                    'message' => "Lien de confirmation bien envoyee"
                ];
            }else{
                throw new Error('Email non valide');
            }
       }

    /**
       * rejet du Mot de Passe
       * 
       */

       public function resetPassword(Request $request){
            $email = $request->route('email');
            $token = $request->route('token');
            $user = User::where('email', $email)->first();
            $takeTokenid = explode('|', $token)[0];
            $verifyToken = DB::table('personal_access_tokens')
            ->where('id', $takeTokenid)->get();
            if($verifyToken){
                    if($user){
                        $password = time().rand(9000000000, 999999999999999);
                        $new_password = bcrypt($password);
                        DB::update('update users set password = ? where email = ?', [$new_password, $email]);
                        return [
                            'message' => 'Votre mot de passe a ete reinitialisee',
                            'mdp' => $password
                        ];
                    }else{
                        throw new Error('Erreur d\'authentification');
                    }
            }else{
                throw new Error('Action non autorise');
            }
        }

        /**
         * 
         * Recupere la liste d'utilisateurs
         */

         public function usersList() {
            $users = DB::table('users', 'users')
            ->join('servs', 'servs.s_id', '=', 'users.id_serv')
            ->join('dirs', 'dirs.d_id', '=', 'users.id_dir')
            ->get(['*', 'users.created_at', 'users.updated_at']);

            return $users;
         }

}
