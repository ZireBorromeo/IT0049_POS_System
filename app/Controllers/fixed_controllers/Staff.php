<?php

namespace App\Controllers;

use App\Models\UserModel;

class Staff extends BaseController
{
    private function saveAvatar(?string $oldAvatar = null): ?string
    {
        $file = $this->request->getFile('avatar');
        if (! $file || ! $file->isValid() || $file->hasMoved()) return $oldAvatar;
        if (! in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'], true)) return $oldAvatar;
        if ($file->getSizeByUnit('mb') > 2) return $oldAvatar;

        $dir = FCPATH . 'uploads/avatars';
        if (! is_dir($dir)) mkdir($dir, 0755, true);
        $name = $file->getRandomName();
        $file->move($dir, $name);
        return $name;
    }

    private function rules(bool $editing = false, ?int $id = null): array
    {
        $usernameRule = 'required|min_length[3]|max_length[50]|alpha_numeric_punct';
        if (! $editing) $usernameRule .= '|is_unique[users.username]';
        else $usernameRule .= '|is_unique[users.username,id,' . $id . ']';

        $rules = [
            'username' => $usernameRule,
            'full_name' => 'required|min_length[2]|max_length[100]',
            'avatar' => 'if_exist|max_size[avatar,2048]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]',
        ];
        if (! $editing || $this->request->getPost('password') !== '') {
            $rules['password'] = 'required|min_length[8]|max_length[255]';
            $rules['password_confirm'] = 'required|matches[password]';
        }
        return $rules;
    }

    public function index()
    {
        return $this->render('staff/index', [
            'pageTitle' => 'Staff',
            'staff' => (new UserModel())->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules())) {
                return $this->render('staff/form', ['pageTitle' => 'Add Staff', 'validation' => $this->validator, 'member' => null]);
            }
            $model = new UserModel();
            $model->insert([
                'username' => trim((string) $this->request->getPost('username')),
                'full_name' => trim((string) $this->request->getPost('full_name')),
                'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
                'avatar' => $this->saveAvatar(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to('/staff')->with('success', 'Staff account created.');
        }
        return $this->render('staff/form', ['pageTitle' => 'Add Staff', 'member' => null]);
    }

    public function edit(int $id)
    {
        $model = new UserModel();
        $member = $model->find($id);
        if (! $member) return redirect()->to('/staff')->with('error', 'Staff member not found.');

        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules(true, $id))) {
                return $this->render('staff/form', ['pageTitle' => 'Edit Staff', 'validation' => $this->validator, 'member' => $member]);
            }
            $update = [
                'username' => trim((string) $this->request->getPost('username')),
                'full_name' => trim((string) $this->request->getPost('full_name')),
                'avatar' => $this->saveAvatar($member['avatar']),
            ];
            if ($this->request->getPost('password') !== '') {
                $update['password'] = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);
            }
            $model->update($id, $update);

            if ((int) session()->get('user_id') === $id) {
                session()->set(['username' => $update['username'], 'full_name' => $update['full_name'], 'avatar' => $update['avatar']]);
            }
            return redirect()->to('/staff')->with('success', 'Staff account updated.');
        }
        return $this->render('staff/form', ['pageTitle' => 'Edit Staff', 'member' => $member]);
    }

    public function delete(int $id)
    {
        if ((int) session()->get('user_id') === $id) {
            return redirect()->to('/staff')->with('error', 'You cannot delete the account you are currently using.');
        }
        if (db_connect()->table('sales')->where('sold_by', $id)->countAllResults() > 0) {
            return redirect()->to('/staff')->with('error', 'This staff member has sales history and cannot be deleted.');
        }
        $member = (new UserModel())->find($id);
        if ($member) {
            (new UserModel())->delete($id);
            if (! empty($member['avatar'])) {
                $path = FCPATH . 'uploads/avatars/' . basename($member['avatar']);
                if (is_file($path)) @unlink($path);
            }
        }
        return redirect()->to('/staff')->with('success', 'Staff account deleted.');
    }
}
