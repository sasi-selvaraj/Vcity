<?php

namespace App\Imports;

use App\Models\Director;
use App\Models\Marketer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MarketerImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation
{
    /**
     * @param Collection $collection
     */
    use  Importable, SkipsFailures;

    private $errors = [];
    public function __construct()
    {
        $this->row['row'] = 1;
        $this->row['messages'] = [];
        $this->row['success'] = 1;
    }

    public function model(array  $row)
    {
        $marketerId = $row['member_of'];
        $parts = explode(' ', $marketerId);

        $vc = $parts[0]; // VC00001
        
        $name = implode(' ', array_slice($parts, 1)); // V.S.RAAKHAVAN

        $this->row['row'] = $this->row['row'] + 1;
        if (!empty($row['member_of'])) {
            $existingMarketer = Marketer::where('marketer_vcity_id', str_replace(['MC', 'VC'], 'V', $vc))->first();
            if (!$existingMarketer) {
                return null;
            }
        }
        $existingMarketer1 = Marketer::where('marketer_vcity_id', str_replace(['MC', 'VC'], 'V', $row['marketer_no']))->first();
        if ($existingMarketer1) {
            return null;
        }
        $creationDate = Carbon::now();
        $renewalDate = $creationDate->addYear();
        $marketers = new Marketer();
        $marketers->name = isset($row['marketer_name']) ? $row['marketer_name'] : '';
        $marketers->marketer_vcity_id = isset($row['marketer_no']) ? str_replace(['MC', 'VC'], 'V', $row['marketer_no']) : '';
        $marketers->email = isset($row['email_id']) ? $row['email_id'] : '';
        $marketers->father_name = isset($row['father_name']) ? $row['father_name'] : '';
        $marketers->qualification = isset($row['educational_qualification']) ? $row['educational_qualification'] : '';

        $marketers->date = (isset($row['date']) && !empty($row['date'])) ? Carbon::createFromTimestamp(((int)$row['date']- 25569) * 86400)->format('Y-m-d') : date("Y-m-d");
        $marketers->dob = isset($row['date_of_birth']) ? Carbon::createFromTimestamp(((int)$row['date_of_birth']- 25569) * 86400)->format('Y-m-d') : null;

        $marketers->address = isset($row['address']) ? $row['address'] : '';
        $marketers->acc_no = isset($row['bank_account_no']) ? $row['bank_account_no'] : '';
        $marketers->ifsc_code = isset($row['ifsc']) ? $row['ifsc'] : '';
        $marketers->branch = isset($row['branch']) ? $row['branch'] : '';
        $marketers->city = isset($row['city']) ? $row['city'] : '';
        $marketers->pincode = isset($row['pincode']) ? $row['pincode'] : '';
        $marketers->mobile_no = isset($row['mobile_phone']) ? $row['mobile_phone'] : '';
        $marketers->aadhar_no = isset($row['aadhar_no']) ? $row['aadhar_no'] : '';
        $marketers->pan_no = isset($row['pan_no']) ? $row['pan_no'] : '';
        $marketers->designation = isset($row['designation']) ? $row['designation'] : '';
        $marketers->renewal_date = isset($renewalDate) ? $renewalDate : '';
        $marketer = Marketer::where('marketer_vcity_id', str_replace(['MC', 'VC'], 'V', $vc))->first();
        if ($marketer) {
            switch ($marketer->designation) {
                case 'CD':
                    $marketers->chief_director = $marketer->id;
                    $marketers->chief_vcity_id = $marketer->id;
                    break;
                case 'SD':
                    $marketers->senior_director = $marketer->id;
                    $marketers->senior_vcity_id = $marketer->id;
                    $marketers->chief_director = $marketer->chief_director;
                    $marketers->chief_vcity_id = $marketer->chief_director;
                    break;
                case 'Dir':
                    $marketers->director = $marketer->id;
                    $marketers->director_vcity_id = $marketer->id;
                    $marketers->senior_director = $marketer->senior_director;
                    $marketers->senior_vcity_id = $marketer->senior_director;
                    $marketers->chief_director = $marketer->chief_director;
                    $marketers->chief_vcity_id = $marketer->chief_director;
                    break;
                case 'AD':
                    $marketers->assistant_director = $marketer->id;
                    $marketers->ad_vcity_id = $marketer->id;
                    $marketers->director = $marketer->director;
                    $marketers->director_vcity_id = $marketer->director;
                    $marketers->senior_director = $marketer->senior_director;
                    $marketers->senior_vcity_id = $marketer->senior_director;
                    $marketers->chief_director = $marketer->chief_director;
                    $marketers->chief_vcity_id = $marketer->chief_director;
                    break;
                case 'CRM':
                    $marketers->crm = $marketer->id;
                    $marketers->crm_vcity_id = $marketer->id;
                    $marketers->assistant_director = $marketer->assistant_director;
                    $marketers->ad_vcity_id = $marketer->assistant_director;
                    $marketers->director = $marketer->director;
                    $marketers->director_vcity_id = $marketer->director;
                    $marketers->senior_director = $marketer->senior_director;
                    $marketers->senior_vcity_id = $marketer->senior_director;
                    $marketers->chief_director = $marketer->chief_director;
                    $marketers->chief_vcity_id = $marketer->chief_director;
                    break;
                default:
                    break;
            }
        }

        if ($marketers->save()) {
            if ($row['designation'] != 'CC' && $row['designation'] != 'CRM') {
                $director = new Director();
                $director->name = $marketers->name;
                $director->email = $marketers->email;
                $director->director_id = $marketers->marketer_vcity_id;
                $director->mobile_no = $marketers->mobile_no;
                $director->designation = $marketers->designation;
                $director->address = $marketers->address;
                $director->marketer_id = $marketers->id;
                $director->status = 1;
                $director->save();
            }
        }
    }


    private function isValidRow(array $row)
    {
        // Check for required fields and any other validations
        return isset($row['marketer_name']) && isset($row['name']) && isset($row['designation']);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function rules(): array
    {
        return [
            // 'marketer_no' => 'required',
            // 'marketer_name' => 'required|min:3',
            // 'email' => 'nullable|email|unique:marketers,email',
            // 'father_name' => 'nullable|min:3',
            // 'pincode' => 'nullable|numeric|digits:6',
            // 'mobile_phone' => 'required|numeric|digits:10',
            // 'aadhar_no' => 'nullable|numeric|digits:12',
            // 'date' => 'nullable|date',
            // 'date_of_birth' => 'nullable'
            // 'director_email' => 'required',
        ];
    }


    public function getRowCount(): array
    {
        return $this->row;
    }

    function isArrayOfIntegers($arr)
    {
        // Create a Laravel Collection from the array
        $collection = collect($arr);

        // Use the every() method to check if every element is an integer
        return $collection->every(function ($item) {
            return is_int($item);
        });
    }
}
