<?php

use App\Http\Controllers\MedicineController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BHYTController;
use App\Http\Controllers\BrandProduct;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//custormer
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', [HomeController::class, 'index']);
// Route::get('/home', [HomeController::class, 'index']);
        
//admin
Route::get('/admin', [AdminController::class, 'index']);
Route::get('/dashboard', [AdminController::class, 'show_dashboard']);
// dang nhap
Route::post('/admin-dashboard', [AdminController::class, 'dashboard']);
// dang xuat 
Route::get('/logout', [AdminController::class, 'logout']);
//Clinic
Route::get('/add-clinic', [ClinicController::class, 'addClinic']);
Route::get('/list-clinic', [ClinicController::class, 'listClinic']);
Route::post('/save-clinic', [ClinicController::class, 'saveClinic']);
Route::get('/edit-category-product/{category_update_id}', [ClinicController::class, 'editCategory']);
Route::post('/update-category-product/{category_update_id}', [ClinicController::class, 'updateCategory']);
Route::get('/delete-category-product/{category_update_id}', [ClinicController::class, 'deleteCategory']);

//Rooms
Route::get('/add-room', [RoomController::class, 'addRoom']);
Route::get('/list-room', [RoomController::class, 'listRoom']);
Route::post('/save-room', [RoomController::class, 'saveRoom']);
Route::get('/edit-room/{room_id}', [RoomController::class, 'editRoom']);
Route::post('/update-room/{room_id}', [RoomController::class, 'updateRoom']);
Route::get('/delete-room/{room_id}', [RoomController::class, 'deleteRoom']);

//service
Route::get('/add-service', [ServiceController::class, 'addService']);
Route::get('/list-service', [ServiceController::class, 'listService']);
Route::post('/save-service', [ServiceController::class, 'saveService']);
Route::get('/edit-service/{service_id}', [ServiceController::class, 'editService']);
Route::get('/delete-service/{service_id}', [ServiceController::class, 'deleteService']);
Route::post('/update-service/{service_id}', [ServiceController::class, 'updateService']);

//medicine
Route::get('/add-medicine', [MedicineController::class, 'addMedicine']);
Route::get('/list-medicine', [MedicineController::class, 'listMedicine']);
Route::post('/save-medicine', [MedicineController::class, 'saveMedicine']);
Route::get('/edit-medicine/{medicine_id}', [MedicineController::class, 'editMedicine']);
Route::get('/delete-medicine/{medicine_id}', [MedicineController::class, 'deleteMedicine']);
Route::post('/update-medicine/{medicine_id}', [MedicineController::class, 'updateMedicine']);

// prescription
Route::get('/add-prescription', [PrescriptionController::class, 'addPrescription']);
Route::get('/list-prescription', [PrescriptionController::class, 'listPrescription']);
Route::post('/save-prescription', [PrescriptionController::class, 'savePrescription']);
Route::get('/edit-prescription/{precription_id}', [PrescriptionController::class, 'editPrescription']);
Route::post('/update-prescription/{precription_id}', [PrescriptionController::class, 'updatePrescription']);
Route::get('/delete-prescription/{precription_id}', [PrescriptionController::class, 'deletePrescription']);

//detail_prescription
Route::get('/detail-prescription/{id}', [PrescriptionController::class, 'detailPrescription'])
    ->name('prescription.detail');
Route::post('/save-detailprescription/{id}', [DetailController::class, 'saveDetailPrescription']);
Route::get('/edit-prescription-detail/{id}', [DetailController::class, 'editPrescriptionDetail']);
Route::get('/delete-prescription-detail/{id}', [DetailController::class, 'deletePrescriptionDetail']);

//doctor
Route::get('/add-doctor', [DoctorController::class, 'addDoctor']);
Route::get('/list-doctor', [DoctorController::class, 'listDoctor']);
Route::post('/save-doctor', [DoctorController::class, 'saveDoctor']);
Route::get('/edit-doctor/{doctor_id}', [DoctorController::class, 'editDoctor']);
Route::post('/update-doctor/{doctor_id}', [DoctorController::class, 'updateDoctor']);
Route::get('/delete-doctor/{doctor_id}', [DoctorController::class, 'deleteDoctor']);

//patient
Route::get('/add-patient', [PatientController::class, 'addPatient']);
Route::get('/list-patient', [PatientController::class, 'listPatient']);
Route::post('/save-patient', [PatientController::class, 'savePatient']);
Route::get('/edit-patient/{patient_id}', [PatientController::class, 'editPatient']);
Route::post('/update-patient/{patient_id}', [PatientController::class, 'updatePatient']);
Route::get('/delete-patient/{patient_id}', [PatientController::class, 'deletePatient']);
// statusPatient
Route::get('/out-patient/{patient_id}', [PatientController::class, 'outPatient']);
Route::get('/in-patient/{patient_id}', [PatientController::class, 'inPatient']);

//bhyt
Route::get('/list-bhyt', [BHYTController::class, 'listBhyt']);
// Route::post('/save-patient', [BHYTController::class, 'savePatient']);
Route::get('/edit-bhyt/{bhyt_id}', [BHYTController::class, 'editBhyt']);
Route::post('/update-bhyt/{bhyt_id}', [BHYTController::class, 'updateBhyt']);
Route::get('/delete-bhyt/{bhyt_id}', [BHYTController::class, 'deleteBhyt']);

// // product
// Route::get('/add-product', [ProductController::class, 'addProduct']);
// Route::get('/list-product', [ProductController::class, 'listProduct']);
// Route::post('/save-product', [ProductController::class, 'saveProduct']);
// Route::get('/edit-product/{product_id}', [ProductController::class, 'editProduct']);
// Route::get('/delete-product/{product_id}', [ProductController::class, 'deleteProduct']);
// Route::post('/update-product/{product_id}', [ProductController::class, 'updateProduct']);
// // an hien danh muc
// Route::get('/active-product/{product_id}', [ProductController::class, 'active_Product']);
// Route::get('/unactive-product/{product_id}', [ProductController::class, 'unactive_Product']);