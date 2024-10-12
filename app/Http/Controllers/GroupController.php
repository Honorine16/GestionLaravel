<?php

namespace App\Http\Controllers;

use App\Events\UserAddedToGroup;
use App\Http\Resources\UserResource;
use App\Interfaces\GroupInterface;
use App\Mail\FileUploadedNotification;
use App\Mail\GroupInviteMail;
use App\Mail\NewMemberNotification;
use App\Models\File;
use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\GroupMember;
use App\Models\User;
use App\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GroupController extends Controller
{
    private GroupInterface $groupInterface;

    public function __construct(GroupInterface $groupInterface)
    {
        $this->groupInterface = $groupInterface;
    }

    public function store(Request $request)

    {
        $userId = auth()->id();

        $data = [
            'name' => $request->name,
            'description_group' => $request->description_group,
            'user_id' => $userId,
        ];

        DB::beginTransaction();
        try {
            $group = $this->groupInterface->create($data);

            DB::commit();

            return ApiResponse::sendResponse(true, [new UserResource($group)], 'Groupe créé avec succès!', 201);
        } catch (\Throwable $th) {
            return $th;
            return ApiResponse::rollback($th);
        }
    }

    public function find(Request $request)
    {
        $data = [
            'group' => $request->group,
            'groupId' => $request->groupId,
        ];

        DB::beginTransaction();
        try {
            $user = $this->groupInterface->find($data);

            DB::commit();

            if ($user)
                return ApiResponse::sendResponse(
                    $user,
                    [],
                    'Opération effectuée.',
                    200
                );
            else
                return ApiResponse::sendResponse(
                    $user,
                    [],
                    'Informations incorrectes.',
                    200
                );
        } catch (\Throwable $th) {
            return ApiResponse::rollback($th);
        }
    }
    public function show($id)
    {
        return Group::findOrFail($id);
    }

    public function addMember(Request $request, $groupId)
    {
        $request->validate([
            'email' => 'required',

        ]);


        $group = Group::findOrFail($groupId);
        $otp = rand(000000, 999999);



        $user = User::where('email', ['email' => $request->email])->first();

        if ($user) {
            $group->members()->attach($user->id);
            //envoyer un message par mail pour informer de son ajout dans un groupe
            Mail::to(['email' => $request->email])->send(new NewMemberNotification($group, $user));

            //appeler la fonction qui notifie tous les membres
            return ApiResponse::sendResponse(
                true,
                ['group' => $group, 'user' => $user],
                'Membre ajouté avec succès',
                201
            );
        } else {
            $data = [
                'email' => $request->email,
                'otp' => $otp,
                'group_id' => $groupId,
            ];
            $inviteUser = GroupInvitation::create($data);


            Mail::to($data['email'])->send(new GroupInviteMail($group, $otp));


            return ApiResponse::sendResponse(
                true,
                ['inviteUser' => $inviteUser,],
                'Invitation envoyée',
                201
            );
        }
    }

    // public function showMember($groupId)
    // {
    //     $group = GroupMember::with('users')->findOrFail($groupId);
    //     return response()->json($group);
    // }

    public function index()
    {
        $groups = Group::all();
        return response()->json($groups);
    }


    // public function verifyOTP(Request $request, $groupId)
    // {
    //     $group = [
    //         'groupId' => $request->groupId,
    //         'otp' => $request->code,
    //     ];

    //     DB::beginTransaction();
    //     try {
    //         $group = $this->groupInterface->verifyOTP($groupId);

    //         DB::commit();
    //         if (!$group) {

    //             return ApiResponse::sendResponse(
    //                 false,
    //                 [],
    //                 'Code de confirmation invalide.',
    //                 200
    //             );
    //         }
    //         return ApiResponse::sendResponse(
    //             true,
    //             [new UserResource($group)],
    //             'Accès au groupe accordé !',
    //             200
    //         );
    //     } catch (\Throwable $th) {
    //         return ApiResponse::rollback($th);
    //     }
    // }

    public function logout()
    {
        $user = User::find(auth()->user()->getAuthIdentifier());
        $user->tokens()->delete();

        return ApiResponse::sendResponse(
            true,
            [],
            'Utilisateur déconnecté.',
            200
        );
    }
}

  