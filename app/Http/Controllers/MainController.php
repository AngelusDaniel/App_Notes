<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PhpParser\Node\Stmt\TryCatch;

class MainController extends Controller
{

  public function index(){

    $id = session("user.id");


    $notes = User::find($id)
      ->notes()->whereNull("deleted_at")
      ->orderBy("id", "desc")->get()->toArray();

    return view("home", ["notes" => $notes]);

  }

  public function newNote(){

    return view("new_note");

  }

  public function newNoteSubmit(Request $request){

    $error = [
      "text_title" => ["required", "max:100"],
      "text_note" => ["required", "max:1200"]
    ];
    
    $feedback = [
      "text_title.required" => "O campo Note Title não pode ser vazio",
      "text_title.max" => "O titulo não pode ter mais do que :max caracteres",
      "text_note.required" => "O campo Note Text não pode ser vazio",
      "text_note.max" => "O texto da nota não pode ter mais do que :max caracteres",
    ];

    $request->validate($error, $feedback);

    $userId = session("user.id");

    $note = new Note();

    $note->user_id = $userId;
    $note->title = $request->text_title;
    $note->text = $request->text_note;
    $note->save();

    return redirect(route("home"));



  }


  public function editNote($id){

    $id = Operations::decryptId($id);

    $note = Note::find($id);

    return view("edit_note", ["note" => $note]);


  }

  public function editNoteSubmit(Request $request){

    $error = [
      "text_title" => ["required", "max:100"],
      "text_note" => ["required", "max:1200"]
    ];
    
    $feedback = [
      "text_title.required" => "O campo Note Title não pode ser vazio",
      "text_title.max" => "O titulo não pode ter mais do que :max caracteres",
      "text_note.required" => "O campo Note Text não pode ser vazio",
      "text_note.max" => "O texto da nota não pode ter mais do que :max caracteres",
    ];

    if($request->note_id == null){

      return redirect()->route("home");

    }

    $request->validate($error, $feedback);

    $noteId = Operations::decryptId($request->note_id);

    $note = Note::find($noteId);

    $note->title = $request->text_title;

    $note->text = $request->text_note;

    $note->save();

    return redirect()->route("home");

    


  }


  public function deleteNote($id){

    //$id = $this->decryptId($id);

    $id = Operations::decryptId($id);

    $note = Note::find($id);

    return view("delete_note", ["note" => $note]);

  }

  public function deleteNoteConfirm($id){
    
    $id = Operations::decryptId($id);

    $note = Note::find($id);

    //hard delete
    //$note->delete();

    //soft delete 1
    // $note->deleted_at = date("Y:m:d H:i:s");
    // $note->save();

    //hard delete 2 com softDeletes no model
    //$note->forceDelete();



    //soft delete 2
    $note->delete();

    return redirect()->route("home");

  }


  // private function decryptId($id){

  //   try {
      
  //     $id = Crypt::decrypt($id);

  //   } catch (DecryptException $e) {
      
  //     return redirect()->route("home");

  //   }

  //   return $id;

  // }

}
