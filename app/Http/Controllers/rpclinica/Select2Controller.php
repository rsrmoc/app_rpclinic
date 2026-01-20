<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\CID;
use Illuminate\Http\Request;

class Select2Controller extends Controller
{
    public function cid() {
        $cids = CID::paginate(25);
        $select2 = array_map(fn($cid) => ["id" => $cid->cd_cid, "text" => $cid->ds_cid], $cids->items());

        return response()->json($select2);
    }
}
