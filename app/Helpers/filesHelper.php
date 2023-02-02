<?php


function saveFile($file) {

    $completeFileName = $file->getClientOriginalName();
    $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();
    $compPic = str_replace('','_', $fileNameOnly).'-'.rand().'_'.time().'.'.$extension;

    $path = $file->storeAs('public/files', $compPic);
    //retourne le nom complet du fichier
    return $compPic;
}

function updatFile($name){

   // $value='files/'+$name;
    if(File::exists(public_path('storage/files/'.$name))){

        File::delete(public_path('storage/files/'.$name));
        /*
            Delete Multiple files this way
            Storage::delete(['upload/test.png', 'upload/test2.png']);
        */
    }
}

//fonction pour supprimer un fichier à partir de son nom

function deleteFile($name){

    if(File::exists(public_path('storage/files/'.$name))){

        File::delete(public_path('storage/files/'.$name));

    }
}


?>
