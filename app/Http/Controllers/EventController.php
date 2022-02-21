<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {

        $events = Event::all(); // -> Esse método(all) pega todos os registros da tabela events.
        return view('welcome', ['events' => $events]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $event = new Event();

        $event->title = $request->title;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;

        //Image Upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension(); // Pega a extensão do arquivo.
            $imageName = md5($requestImage->getClientOriginalName()) . strtotime('now') . '.' . $extension; //md5 faz uma hash da imagem e strtotime pega a hora atual.
            $requestImage->move(public_path('img/events'), $imageName); // Move o arquivo para a pasta img/events.
            $event->image = $imageName; // Salva o nome da imagem no banco de dados.
        }

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', ['event' => $event]);
    }
}
