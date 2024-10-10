<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Interfaces\GroupInterface;
use App\Mail\FileUploadedNotification;
use App\Mail\GroupInviteMail;
use App\Mail\NewMemberNotification;
use App\Models\File;
use App\Models\Group;
use App\Models\GroupInvitation;
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
            'emails' => 'required',
            // 'emails.*' => 'email',
        ]);


        $group = Group::findOrFail($groupId);
        $otp = rand(000000, 999999);
        $data = [
            'name' => $request->name,
            'email' => $request->emails,
            'password' => $otp,
        ];

        // foreach ($request->emails as $email) {
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            // $group->members->attach($user->id);
        } else {
            $user = User::create($data);

            // $group->users->attach($user->id);
            Mail::to($data['email'])->send(new GroupInviteMail($group, $otp));
            // }

        }



        // $existingMembers = $group->users;

        // foreach ($existingMembers as $member) {
        //     Mail::to($member->email)->send(new NewMemberNotification($user->name));
        // }

        return response()->json(['message' => 'Members added and notified.']);
        foreach ($group->users as $member) {
            if ($member->id !== $newMember->id) { // Évite de notifier le nouveau membre
                $member->notify(new NewMemberNotification($newMember));
            }
        }
    }

    public function addUserToGroup(Request $request, $groupId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'email' => 'required'
        ]);

        $group = Group::findOrFail($groupId);
        $group->users()->attach($request->user_id);
        

        return response()->json(['message' => 'Membre ajouté avec succès dans le groupe.']);
    }


    public function showMember($groupId)
    {
        // Afficher un groupe spécifique avec ses membres
        $group = Group::with('users')->findOrFail($groupId);
        return response()->json($group);
    }

    public function index()
    {

        $groups = Group::all();
        return response()->json($groups);
    }

    // public function show()
    // {
    //     $member = Group::all();
    //     return response()->json($member);
    // }


    public function verifyOTP(Request $request, $groupId)
    {
        $group = [
            'groupId' => $request->groupId,
            'otp' => $request->code,
        ];

        DB::beginTransaction();
        try {
            $group = $this->groupInterface->verifyOTP($groupId);

            DB::commit();
            if (!$group) {

                return ApiResponse::sendResponse(
                    false,
                    [],
                    'Code de confirmation invalide.',
                    200
                );
            }
            return ApiResponse::sendResponse(
                true,
                [new UserResource($group)],
                'Accès au groupe accordé !',
                200
            );
        } catch (\Throwable $th) {
            return ApiResponse::rollback($th);
        }
    }

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
