<?php

namespace App\Livewire;

use Livewire\Component;
use Intervention\Image\ImageManager;
use \Intervention\Image\Drivers\Gd\Driver;
use Livewire\WithFileUploads;

class LandingPage extends Component
{
    use WithFileUploads;
    public $photo;
    public $statut = '';

    protected $rules = [
        'photo' => 'required|image|max:20400',
    ];

    // protected $listeners = ['message'];

    // public function message( $message ){
    //     $this->statut = $message;
    // }

    // public function mount(){
    //     $this->statut = 'Pret a l\'emploi';
    // }

    public function scaleImage(){
        $this->validate($this->rules);

        $nom =  $this->photo->getClientOriginalName();
        $this->photo->storeAs('photos', $nom, 'public');

        try{
            $manager = new ImageManager(new Driver());

            // $this->emit('message','Traitement d\'image(s)...');
            $image = $manager->read(storage_path('app/public/photos/'.$nom ));
            sleep(1);

            // $this->emit('message','Changement des dimensions...');
            $image->scale(height: 50);

            // $this->emit('message','Sauvegarde d\'image(s)');
            $image->save(storage_path('app/public/photos_edited/'.$nom ));
            sleep(1);

            // $this->emit('message','Opération est éxecutée avec succès !');

        }catch(\Exception $e){
            dump( $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.landing-page');
    }
}
