<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movies;
use Validator;
use App\Helpers\ArrayHelper;
use App\Helpers\DateHelper;

class MoviesController extends Controller
{
    protected $validateMsg = [];

    /**
     * Get movie list.
     *
     * @author Chigs Patel <info@webnappdev.in>
     * @Date 3rd Nov 2018
     */
    public function getList()
    {
        $moviesData = Movies::getMoviesList();
        return ['moviesData' => $moviesData];
    }

    /**
     * Get movie detail by id.
     *
     * @param  int   $id
     * @return array
     * @author Chigs Patel <info@webnappdev.in>
     * @Date 3rd Nov 2018
     */
    public function getDetail($id)
    {
        $moviesData = Movies::where(['deleted_at' => '', 'id' => $id])->first();
        return ['moviesData' => $moviesData];
    }

    /**
     * Create movie record.
     *
     * @param Request $request
     * @return json
     * @author Chigs Patel <info@webnappdev.in>
     * @Date 3rd Nov 2018
     */
    public function createMovies(Request $request)
    {
        $params = $request->all();
        $isValidate = $this->validateMoviesData($params);
        if (!$isValidate) {
            return $this->responseError($this->validateMsg);
        }

        $isAdd = Movies::addUpdateMovies($params);
        if ($isAdd)
            return $this->responseSuccess('Add Records Successfull');
        return $this->responseError('Something went wrong!');
    }

    /**
     * Update movie record by id.
     *
     * @param Request $request
     * @param int     $moviesId
     * @return json
     * @author Chigs  Patel <info@webnappdev.in>
     * @Date 3rd Nov 2018
     */
    public function updateMovies(Request $request, $moviesId)
    {
        if (!$moviesId) {
            return $this->responseError('Params Error!');
        }

        $params = $request->all();
        $isValidate = $this->validateMoviesData($params);
        if (!$isValidate) {
            return $this->responseError($this->validateMsg);
        }

        $isUpdate = Movies::addUpdateMovies($params, $moviesId);

        if($isUpdate)
            return $this->responseSuccess('Update Records Successfull');
        return $this->responseError('Something went wrong!');
    }

    /**
     * Validate movie data.
     *
     * @param array $params
     * @return bool
     * @author Chigs Patel <info@webnappdev.in>
     * @Date 3rd Nov 2018
     */
    private function validateMoviesData($params = [])
    {
        $validator = Validator::make($params, [
            'title'       => 'required|max:255',
            'year'        => 'required|int',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            $this->validateMsg = $validator->errors()->all();
            return false;
        }

        return true;
    }

    /**
     * Delete movie record by id.
     *
     * @param int    $id
     * @return json
     * @author Chigs Patel <info@webnappdev.in>
     * @Date 3rd Nov 2018
     */
    public function deleteMovies($id) {
        $isDeleted = Movies::where('id', $id)->update(['is_active' => Movies::IS_IN_ACTIVE, 'deleted_at' => Date('Y-m-d H:i:s')]);

        if ($isDeleted)
        return $this->responseSuccess('Deleted Records Successfull');
        return $this->responseError('Something went wrong!');
    }

    /**
     * Render movie list.
     *
     * @return void
     * 
     * @author Chigs Patel <info@webnappdev.in>
     * @date   November 3 2018 9:37 PM
     */
    public function movieList()
    {
        return view('movieList');
    }

    /**
     * Image upload.
     *
     * @param Request $_FILES
     * @return string
     * @author Chigs Patel <info@webnappdev.in>
     * @Date 3rd Nov 2018
     */
    public function imageUpload(Request $request)
    {
        $image           = [];
        $validExtensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
        $basePath        = url('public/uploads'); // upload directory
        $path            = public_path('/uploads'); // upload directory
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            $this->validateMsg = $validator->errors()->all();
            $image['code'] = -1;
            $image['msg'] = implode(",", $this->validateMsg);
            return $image;
        }

        if($_FILES['image'])
        {
            $img = $_FILES['image']['name'];
            $tmp = $_FILES['image']['tmp_name'];
            // get uploaded file's extension
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            // can upload same image using rand function
            $finalImage = rand(1000,1000000).$img;
            $finalImage = array_first(ArrayHelper::fileSeoName([$finalImage]));
        // check's valid format
            if(in_array($ext, $validExtensions)) 
            { 
                $path = $path."/".strtolower($finalImage); 
                if(move_uploaded_file($tmp, $path)) 
                {
                    $image['code'] = 0;
                    $image['data']['imageName'] = $basePath."/".$finalImage;
                }
            }

            return $image;
        }
    }

    public function test() {
        $array = ['base' => ['name' =>'chirag', 'last_name' => 'Bhalara', 'Tel' => 9537178057]];
        $array2 = ['base' => ['name' =>'chirag111', 'last_name' => 'Bhalara222', 'email' =>'dd@ff.in']];
        $json = ArrayHelper::jsonFormate($array, true);
        $arrayRes = ArrayHelper::jsonFormate($json, false, true);
        //print_r(ArrayHelper::ArrayToStr($arrayRes['base'], ','));
        //print_r(ArrayHelper::StrToArray('a,b,c', ','));
        print_r(ArrayHelper::diffRecursive($array, $array2));
    }

}
