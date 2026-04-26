<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\Auth\Models\User;
use App\Core\Enums\RoleEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserList extends Component
{
    use WithPagination;

    // List
    public string $search = '';

    // Form
    public bool   $showForm  = false;
    public ?int   $editId    = null;
    public string $name      = '';
    public string $email     = '';
    public string $password  = '';
    public string $role      = 'receptionist';

    // Change Password
    public bool   $showPasswordModal = false;
    public ?int   $passwordUserId    = null;
    public string $newPassword       = '';
    public string $newPasswordConfirm = '';

    protected $listeners = ['close-form' => 'closeForm'];

    public function updatingSearch(): void { $this->resetPage(); }

    // ── Form ──────────────────────────────────────────────
    public function openForm(?int $id = null): void
    {
        $this->resetFormFields();
        $this->editId   = $id;
        $this->showForm = true;

        if ($id) {
            $user        = User::findOrFail($id);
            $this->name  = $user->name;
            $this->email = $user->email;
            $this->role  = $user->getRoleNames()->first() ?? 'receptionist';
        }
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetFormFields();
    }

    private function resetFormFields(): void
    {
        $this->editId   = null;
        $this->name     = '';
        $this->email    = '';
        $this->password = '';
        $this->role     = 'receptionist';
        $this->resetErrorBag();
    }

    public function saveUser(): void
    {
        $rules = [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email' . ($this->editId ? ",{$this->editId}" : ''),
            'role'  => 'required|in:admin,receptionist',
        ];

        if (!$this->editId) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules, [
            'name.required'     => 'الاسم مطلوب',
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.email'       => 'البريد الإلكتروني غير صحيح',
            'email.unique'      => 'هذا البريد مستخدم مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min'      => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ]);

        if ($this->editId) {
            $user = User::findOrFail($this->editId);
            $user->update(['name' => $this->name, 'email' => $this->email]);
            $user->syncRoles([$this->role]);
            session()->flash('success', 'تم تعديل الحساب بنجاح');
        } else {
            $user = User::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $user->assignRole($this->role);
            session()->flash('success', 'تم إنشاء الحساب بنجاح');
        }

        $this->closeForm();
    }

    public function deleteUser(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'لا يمكنك حذف حسابك الخاص');
            return;
        }
        User::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف الحساب بنجاح');
    }

    // ── Change Password ───────────────────────────────────
    public function openPasswordModal(int $id): void
    {
        $this->passwordUserId     = $id;
        $this->newPassword        = '';
        $this->newPasswordConfirm = '';
        $this->showPasswordModal  = true;
        $this->resetErrorBag();
    }

    public function closePasswordModal(): void
    {
        $this->showPasswordModal  = false;
        $this->passwordUserId     = null;
        $this->newPassword        = '';
        $this->newPasswordConfirm = '';
    }

    public function changePassword(): void
    {
        $this->validate([
            'newPassword'        => 'required|min:8',
            'newPasswordConfirm' => 'required|same:newPassword',
        ], [
            'newPassword.required'        => 'كلمة المرور الجديدة مطلوبة',
            'newPassword.min'             => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'newPasswordConfirm.required' => 'تأكيد كلمة المرور مطلوب',
            'newPasswordConfirm.same'     => 'كلمتا المرور غير متطابقتين',
        ]);

        User::findOrFail($this->passwordUserId)->update([
            'password' => Hash::make($this->newPassword),
        ]);

        session()->flash('success', 'تم تغيير كلمة المرور بنجاح');
        $this->closePasswordModal();
    }

    public function render()
    {
        $users = User::where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->with('roles')
            ->latest()
            ->get();

        return view('livewire.users.user-list', compact('users'))
            ->layout('components.layouts.app', ['title' => 'إدارة الحسابات']);
    }
}
