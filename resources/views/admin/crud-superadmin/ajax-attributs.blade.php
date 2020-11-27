<?php
use Illuminate\Support\Facades\Validator;
use App\CrudTable;

header('Content-Type: application/json; charset=utf-8');

$rules = [
    'crud_table_id' => 'required|integer|exists:crud_tables,id'
];

$validator = Validator::make(request()->all(), $rules);
if ($validator->fails()) {
    header('HTTP/1.0 404');
    echo json_encode('');
    exit();
}

// On récupère le tableau filtré de la requète
$request = $validator->validate();
$table = CrudTable::find($request['crud_table_id'])->nom;
$attributs = Schema::getColumnListing($table);

header('HTTP/1.0 200');
echo json_encode($attributs);
exit();
// $i = 0;
// foreach($attributs as $id => $attribut){
//     echo $id . '$$';
//     echo $attribut;
//     if($i < count($attributs)-1)
//         echo '|';
//     $i++;
// }
