<?php


class AuthController {

  public static function showLogin() {
    Flight::render('login', [
      'email' => '',
      'erreur_globale' => ''
    ]);
  }

  public static function postLogin() {
    require_once __DIR__ . '/../services/AuthService.php';

    $pdo = Flight::db();
    $auth = new AuthService($pdo);

    $req = Flight::request();
    $email = (string)($req->data->email ?? '');
    $mot_de_passe = (string)($req->data->mot_de_passe ?? '');

    $res = $auth->connecter($email, $mot_de_passe);
    if ($res['ok']) {
      if ((int)$res['id_role'] === 2) {
        Flight::redirect('/admin/voir-tout');
        return;
      }

      Flight::redirect('/accueil');
      return;
    }

    Flight::render('login', [
      'email' => $email,
      'erreur_globale' => $res['message'] ?? 'Erreur de connexion.'
    ]);
  }

  public static function logout() {
    require_once __DIR__ . '/../services/AuthService.php';
    $pdo = Flight::db();
    $auth = new AuthService($pdo);
    $auth->deconnecter();

    Flight::redirect('/login');
  }

  
  public static function showRegister() {
    Flight::render('auth/register', [
      'values' => ['nom'=>'','prenom'=>'','email'=>'','role'=>''],
      'errors' => ['nom'=>'','prenom'=>'','email'=>''],
      'success' => false
    ]);
  }

  public static function validateRegisterAjax() {
    header('Content-Type: application/json; charset=utf-8');

    try {
      $pdo  = Flight::db();
      $repo = new UserRepository($pdo);

      $req = Flight::request();

      $input = [
        'nom' => $req->data->nom,
        'prenom' => $req->data->prenom,
        'email' => $req->data->email,
        'role' => $req->data->role,
      ];

      $res = Validator::validateRegister($input, $repo);

      Flight::json([
        'ok' => $res['ok'],
        'errors' => $res['errors'],
        'values' => $res['values'],
      ]);
    } catch (Throwable $e) {
      http_response_code(500);
      Flight::json([
        'ok' => false,
        'errors' => ['_global' => 'Erreur serveur lors de la validation.'],
        'values' => []
      ]);
    }
  }

  public static function postRegister() {
    $pdo  = Flight::db();
    $repo = new UserRepository($pdo);
    $svc  = new UserService($repo);

    $req = Flight::request();

    $input = [
      'nom' => $req->data->nom,
      'prenom' => $req->data->prenom,
      'email' => $req->data->email,
    ];

    $res = Validator::validateRegister($input, $repo);

    if ($res['ok']) {
      $svc->register($res['values'], (string)$input['password']);
      Flight::render('auth/register', [
        'values' => ['nom'=>'','prenom'=>'','email'=>'','telephone'=>''],
        'errors' => ['nom'=>'','prenom'=>'','email'=>''],
        'success' => true
      ]);
      return;
    }

    Flight::render('auth/register', [
      'values' => $res['values'],
      'errors' => $res['errors'],
      'success' => false
    ]);
  }
  

}
