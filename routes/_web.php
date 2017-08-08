<?php

use Illuminate\Http\Request;

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'api'], function() use($app){

	$app->post('/users', function(Request $request){
		
		$this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|max:16|confirmed'
        ]);

		$data = $request->all();
		$data['password'] = \Hash::make($data['password']);
		$model = \App\User::create($data);
		if($model){
			return response()->json($model, 201);
		} else {
			return response()->json(['Erro'], 400);
		}
	});

	/*
	| @Rotas protegidas com o middleware auth padrão
	*/
	$app->group(['middleware' => 'auth'], function() use($app){
	
		$app->get('/user-auth', function(Request $request){
			return Auth::user();
		});

		$app->get('/clients', function () use ($app) {
		    return ['Ok']; 	
		});

	});

});

/*
| @Rota de login que autentica o usuario
*/
$app->post('/login', function (Request $request) {
    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required'
    ]);
    $email = $request->get('email');
    $password = $request->get('password');
    $user = \App\User::where('email', '=', $email)->first();

    if (!$user || !\Hash::check($password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 400);
    }

    $user->api_token = sha1(str_random(32)) . '.' . sha1(str_random(32));
    $user->save();
    return [
    	'api_token' => $user->api_token
    ];
});




