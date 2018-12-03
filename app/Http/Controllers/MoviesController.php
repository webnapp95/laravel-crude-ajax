<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movies;
use Validator;

class MoviesController extends Controller
{
    protected $validateMsg = [];

    public function getList()
    {
        $moviesData = Movies::getMoviesList();
        return ['moviesData' => $moviesData];
    }

    public function getDetail($id)
    {
        $moviesData = Movies::where(['deleted_at' => '', 'id' => $id])->first();
        return ['moviesData' => $moviesData];
    }

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


    public function deleteMovies($id) {
        $isDeleted = Movies::where('id', $id)->update(['is_active' => Movies::IS_IN_ACTIVE, 'deleted_at' => Date('Y-m-d H:i:s')]);
        if ($isDeleted)
        return $this->responseSuccess('Deleted Records Successfull');
        return $this->responseError('Something went wrong!');
    }

    public function movieList()
    {
        return view('movieList');
    }

    public function imageUpload()
    {
        $image = [];
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
        $basePath = url('public/uploads'); // upload directory
        $path = "C:/xampp/htdocs/movies/public/uploads"; // upload directory

        if($_FILES['image'])
        {
            $img = $_FILES['image']['name'];
            $tmp = $_FILES['image']['tmp_name'];
            // get uploaded file's extension
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            // can upload same image using rand function
            $final_image = rand(1000,1000000).$img;
        // check's valid format
            if(in_array($ext, $valid_extensions)) 
            { 
                $path = $path."/".strtolower($final_image); 
                if(move_uploaded_file($tmp,$path)) 
                {
                    $image['imageName'] = $basePath."/".$final_image;
                }
            }

            return $image;
        }
    }
}
