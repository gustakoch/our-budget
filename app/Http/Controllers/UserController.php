<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userModel;
    private $roleModel;

    public function __construct(User $userModel, RoleModel $roleModel)
    {
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
    }

    public function index()
    {
        if (session('user')['role'] > 2) return redirect('dashboard');
        $users = $this->userModel->getAll();
        $roles = $this->roleModel->getRolesExceptMaster();
        $password = $this->userModel->generatePassword(10);

        return view('users.index', [
            'users' => $users,
            'roles' => $roles,
            'password' => $password
        ]);
    }

    public function passwordGenerate()
    {
        $password = $this->userModel->generatePassword(10);

        return response()->json(['ok' => true, 'password' => $password]);
    }

    public function store()
    {
        $data = request()->all();
        User::create([
            'name' => $data['name_user'],
            'email' => $data['email_user'],
            'password' => password_hash($data['password_user'], PASSWORD_DEFAULT),
            'role' => $data['role_user'] ?? 3,
            'first_access' => $data['first_access_user'],
            'active' => $data['active_user'] ?? 0,
            'created_by' => session('user')['id'] ?? null
        ]);

        return response()->json(['ok' => true, 'message' => 'Usuário cadastrado com sucesso!']);
    }

    public function show($id)
    {
        $user = $this->userModel->getUserInfo($id);
        if (is_null($user)) {
            return response()->json([
                'ok' => false,
                'message' => 'Usuário não encontrado.'
            ]);
        }

        return response()->json(['ok' => true, 'user' => $user]);
    }

    public function update()
    {
        $data = request()->all();
        User::where('id', $data['id_user'])->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'first_access' => $data['first_access'],
            'active' => $data['active']
        ]);
        if (!is_null($data['password_user'])) {
            User::where('id', $data['id_user'])->update([
                'password' => password_hash($data['password_user'], PASSWORD_DEFAULT)
            ]);
        }

        return response()->json(['ok' => true, 'message' => 'Usuário atualizado com sucesso']);
    }
}
