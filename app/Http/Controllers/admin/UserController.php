<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1); // Lấy tham số trang từ URL, mặc định là trang 1
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $users = User::paginate($itemsPerPage);
        $users->appends($request->all());
        if (!$page || ($page > $users->lastPage() || $page < 1)) {
            return redirect('/admin/user?page=1&itemsPerPage=' . $itemsPerPage);
        }
        return view('admin.user.index',compact('users','itemsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'unit_code'=>['required','string','max:255','unique:users'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
                'phone' => ['required', 'string', 'max:10', 'min:10', 'unique:users', 'regex:/^[0-9]*$/'],
            ],
            [
                'phone.regex' => 'Số điện thoại phải là số',
                'password.regex' => 'Mật khẩu có tối thiểu 1 chữ in hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = new User();
        $user->unit_code = $request->input('unit_code');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = Hash::make($request->input('password'));
        $user->role_as = $request->role_as;
        $user->save();
        return redirect('/admin/user/')->with('message', 'Thêm người dùng thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'unit_code'=>['required','string','max:255','unique:users,unit_code,' . $id],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
                'password' => ['nullable', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
                'phone' => ['required', 'string', 'max:10', 'min:10', 'unique:users,phone,' . $id, 'regex:/^[0-9]*$/'],
            ],
            [
                'phone.regex' => 'Số điện thoại phải là số',
                'password.regex' => 'Mật khẩu có tối thiểu 1 chữ in hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = User::findOrFail($id);
        if($request->input('password')==null){
            $user->update([
                'unit_code'=>$request->input('unit_code'),
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'phone'=>$request->input('phone'),
                'role_as'=>$request->role_as,
            ]);
        }else{
            $user->update([
                'unit_code'=>$request->input('unit_code'),
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'phone'=>$request->input('phone'),
                'role_as'=>$request->role_as,
                'password'=>$request->input('password'),
            ]);
        }
        return redirect('admin/user/')->with('message','Cập nhật người dùng thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('message','Xóa người dùng thành công!!!');
    }
    public function search(Request $request)
    {
        $searchInput = $request->input('search');
        $page = $request->input('page', 1); // Lấy tham số trang từ URL, mặc định là trang 1
        // Sử dụng Validator để kiểm tra trường tìm kiếm
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $validator = Validator::make($request->all(), [
            'search' => 'required',
        ], [
            'search.required' => 'Nhập từ khóa tìm kiếm',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // Nếu không có lỗi, tiếp tục thực hiện tìm kiếm và phân trang
        $users = User::where('name', 'like', '%' . $searchInput . '%')
            ->orWhere('unit_code', 'like', '%' . $searchInput . '%')
            ->orWhere('phone', 'like', '%' . $searchInput . '%')
            ->orWhere('email', 'like', '%' . $searchInput . '%')
            ->paginate($itemsPerPage); // Phân trang với 2 mục trên mỗi trang

        $users->appends($request->all());
        if (!$page || ($page > $users->lastPage() || $page < 1)) {
            return redirect('/admin/user/search?search=' . $searchInput . '&itemsPerPage=' . $itemsPerPage . '&page=1'); // Chuyển hướng đến trang 1 nếu trang hiện tại không hợp lệ
        }

        return view('admin.user.index', compact('users', 'itemsPerPage','searchInput'));
    }
}
