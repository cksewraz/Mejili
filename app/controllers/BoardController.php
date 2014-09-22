<?php

class BoardController extends BaseController {

    public function index($id){

        $data['id'] = $id;
        return View::make('users.board', $data);
    }

    public function listBoards(){

        $user = Auth::user();
        $boards = $user->boards;

        $data['boards'] = $boards;

        return View::make('users.boards-list', $data);
    }

    public function addBoard(){

        $name = Input::get('boardName');
        if($name!=''){
            $user = Auth::user();
            $board = new Board(['name' => $name, 'open' => true, 'board_visibility' => 0, 'description' => 'n']);
            $board = $user->boards()->save($board);

            $user->boards()->updateExistingPivot($board->id, ['admin'=> true]);


        }
        return Redirect::route('boards');
    }

    public function getViewModel(){
        $boardId = Input::get('b');
        $board = Board::find($boardId);

        /*$lists[0]['title'] = "First One";
        $card['title'] = "Some card title";
        $card['color'] = 'label-blue';
        $lists[0]['cards'][0] = $card; 
        $data['lists'] = $lists;*/

        $lists = $board->lists()->with('cards')->orderby('position')->get();
        $data['lists'] = $lists;

        return Response::json($data);
    }

}