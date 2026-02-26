<?php
namespace App\Http\Controllers\Visitor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index()
    {
        return view('dashboard.visitor.index');
    }
}
?>