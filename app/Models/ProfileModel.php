<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table      = 'profile';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'user_id',
        'address',
        'gender',
        'religion',
        'parent_email',
        'father_name',
        'mother_name',
        'nis',
        'nisn',
        'barcode_number',
        'profile_photo',
        'created_by_id',
        'updated_by_id',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByBarcodeNumber($id){
        return $this
            ->select("
                student.id as student_id,
                profile.profile_photo,
                profile.parent_email,
                user.last_name,
                user.first_name
            ")
            ->join('user','user.id=profile.user_id')
            ->join('student','profile.id=student.profile_id')
            ->where('barcode_number',$id)
            ->first();
    }
}
