<?php

use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PaymentPatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BHYTController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\PaymentPresController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentServiceController;

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

Route::get('/admin', [AdminController::class, 'index'])->name('login');

// dang nhap
Route::post('/admin-dashboard', [AdminController::class, 'dashboard']);
// dang xuat 
Route::get('/logout', [AdminController::class, 'logout']);
// trang chu
Route::get('/dashboard', [AdminController::class, 'show_dashboard']);
// ->middleware('auth')
//Clinic
Route::get('/add-clinic', [ClinicController::class, 'addClinic']);
Route::get('/list-clinic', [ClinicController::class, 'listClinic']);
Route::post('/save-clinic', [ClinicController::class, 'saveClinic']);
Route::get('/edit-clinic/{clinic_id}', [ClinicController::class, 'editClinic']);
Route::post('/update-clinic/{clinic_id}', [ClinicController::class, 'updateClinic']);
Route::get('/delete-clinic/{clinic_id}', [ClinicController::class, 'deleteClinic']);
Route::get('/print-clinic/{clinic_id}', [ClinicController::class, 'printClinic']);
Route::get('/clinic/payment/{clinic_id}/{status}', [ClinicController::class, 'updatePaymentStatus']);
Route::get('/search-clinic', [ClinicController::class, 'searchClinic']);
Route::get('/check-card', [ClinicController::class, 'checkCardNumber']);

//Rooms
Route::get('/add-room', [RoomController::class, 'addRoom']);
Route::get('/list-room', [RoomController::class, 'listRoom']);
Route::post('/save-room', [RoomController::class, 'saveRoom']);
Route::get('/edit-room/{room_id}', [RoomController::class, 'editRoom']);
Route::post('/update-room/{room_id}', [RoomController::class, 'updateRoom']);
Route::get('/delete-room/{room_id}', [RoomController::class, 'deleteRoom']);
Route::get('/search-room', [RoomController::class, 'searchRoom']);

//beds
Route::get('/add-bed', [BedController::class, 'addBed']);
Route::get('/list-bed', [BedController::class, 'listBed']);
Route::post('/save-bed', [BedController::class, 'saveBed']);
Route::get('/edit-bed/{bed_id}', [BedController::class, 'editBed']);
Route::post('/update-bed/{bed_id}', [BedController::class, 'updateBed']);
Route::get('/delete-bed/{bed_id}', [BedController::class, 'deleteBed']);
Route::get('/search-bed', [BedController::class, 'searchBed']);
//service
Route::get('/add-service', [ServiceController::class, 'addService']);
Route::get('/list-service', [ServiceController::class, 'listService']);
Route::post('/save-service', [ServiceController::class, 'saveService']);
Route::get('/edit-service/{service_id}', [ServiceController::class, 'editService']);
Route::get('/delete-service/{service_id}', [ServiceController::class, 'deleteService']);
Route::post('/update-service/{service_id}', [ServiceController::class, 'updateService']);
Route::get('/search-service', [ServiceController::class, 'searchService']);
//RecordService
Route::get('/list-record-service', [ServiceRecordController::class, 'listRecordService']);
Route::get('/add-record-service', [ServiceRecordController::class, 'addServiceRecord']);
Route::post('/save-record-service', [ServiceRecordController::class, 'saveServiceRecord']);
Route::get('/edit-record-service/{service_record_id}', [ServiceRecordController::class, 'editServiceRecord']);
Route::post('/update-record-service/{service_record_id}', [ServiceRecordController::class, 'updateServiceRecord']);
Route::get('/delete-record-service/{service_record_id}', [ServiceRecordController::class, 'deleteServiceRecord']);
    Route::get('/detail-record-service/{id}', [ServiceRecordController::class, 'detailService'])
    ->name('record_service.detail');
    Route::post('/save-detailservice/{id}', [DetailController::class, 'saveDetailRecordService']);
    Route::get('/edit-service-detail/{id}', [DetailController::class, 'editDetailRecordService']);
    Route::get('/delete-service-detail/{id}', [DetailController::class, 'deleteDetailRecordService']);
    Route::get('/print-service/{id}', [ServiceRecordController::class, 'printService']);
    Route::get('/search-record-service', [ServiceRecordController::class, 'searchRecordService']);


//medicine
Route::get('/add-medicine', [MedicineController::class, 'addMedicine']);
Route::get('/list-medicine', [MedicineController::class, 'listMedicine']);
Route::post('/save-medicine', [MedicineController::class, 'saveMedicine']);
Route::get('/edit-medicine/{medicine_id}', [MedicineController::class, 'editMedicine']);
Route::get('/delete-medicine/{medicine_id}', [MedicineController::class, 'deleteMedicine']);
Route::post('/update-medicine/{medicine_id}', [MedicineController::class, 'updateMedicine']);
Route::get('/search-medicine', [MedicineController::class, 'searchMedicine']);

// prescription
Route::get('/add-prescription', [PrescriptionController::class, 'addPrescription']);
Route::get('/list-prescription', [PrescriptionController::class, 'listPrescription']);
Route::post('/save-prescription', [PrescriptionController::class, 'savePrescription']);
Route::get('/edit-prescription/{precription_id}', [PrescriptionController::class, 'editPrescription']);
Route::post('/update-prescription/{precription_id}', [PrescriptionController::class, 'updatePrescription']);
Route::get('/delete-prescription/{precription_id}', [PrescriptionController::class, 'deletePrescription']);
Route::get('/search-prescription', [PrescriptionController::class, 'searchPrescription']);

//detail_prescription
Route::get('/detail-prescription/{id}', [PrescriptionController::class, 'detailPrescription'])
    ->name('prescription.detail');
Route::post('/save-detailprescription/{id}', [DetailController::class, 'saveDetailPrescription']);
Route::get('/edit-prescription-detail/{id}', [DetailController::class, 'editPrescriptionDetail']);
Route::get('/delete-prescription-detail/{id}', [DetailController::class, 'deletePrescriptionDetail']);
Route::get('/print-prescription/{id}', [PrescriptionController::class, 'printPrescription']);

//doctor
Route::get('/add-doctor', [DoctorController::class, 'addDoctor']);
Route::get('/list-doctor', [DoctorController::class, 'listDoctor']);
Route::post('/save-doctor', [DoctorController::class, 'saveDoctor']);
Route::get('/edit-doctor/{doctor_id}', [DoctorController::class, 'editDoctor']);
Route::post('/update-doctor/{doctor_id}', [DoctorController::class, 'updateDoctor']);
Route::get('/delete-doctor/{doctor_id}', [DoctorController::class, 'deleteDoctor']);
Route::get('/search-doctor', [DoctorController::class, 'searchDoctor']);

//patient
Route::get('/add-patient', [PatientController::class, 'addPatient']);
Route::get('/list-patient', [PatientController::class, 'listPatient']);
Route::post('/save-patient', [PatientController::class, 'savePatient']);
Route::get('/edit-patient/{patient_id}', [PatientController::class, 'editPatient']);
Route::post('/update-patient/{patient_id}', [PatientController::class, 'updatePatient']);
Route::get('/delete-patient/{patient_id}', [PatientController::class, 'deletePatient']);
Route::get('/get-beds/{room_id}', [PatientController::class, 'getBedsByRoom']);

// statusPatient
Route::get('/out-patient/{patient_id}', [PatientController::class, 'outPatient']);
Route::get('/in-patient/{patient_id}', [PatientController::class, 'inPatient']);
Route::get('/search-patient', [PatientController::class, 'searchPatient']);
Route::get('/lock-patient/{id}', [PatientController::class, 'lockPatient']);
Route::get('/print-benhan/{id}', [PatientController::class, 'printfBenhAn']);



//bhyt
Route::get('/list-bhyt', [BHYTController::class, 'listBhyt']);
// Route::post('/save-patient', [BHYTController::class, 'savePatient']);
Route::get('/edit-bhyt/{bhyt_id}', [BHYTController::class, 'editBhyt']);
Route::post('/update-bhyt/{bhyt_id}', [BHYTController::class, 'updateBhyt']);
Route::get('/delete-bhyt/{bhyt_id}', [BHYTController::class, 'deleteBhyt']);
Route::get('/search-bhyt', [BHYTController::class, 'searchBHYT']);

// thanh toán
Route::get('/payment', [PaymentController::class, 'listPayment']);
Route::get('/payment-service', [PaymentServiceController::class, 'listPaymentService']);
Route::get('/payment-medicine', [PaymentPresController::class, 'listPaymentPrescription']);
Route::get('/payment-patient', [PaymentPatientController::class, 'listPaymentPatient']);

Route::get('/get-payment-details/{id}', [PaymentController::class, 'getPaymentDetails']);
Route::post('/process-payment', [PaymentController::class, 'ProcessPayment'])->name('process.payment');
Route::get('/print-invoice/{id}', [PaymentController::class, 'printInvoice']);
Route::get('/view-qrcode/{paymentId}', [PaymentController::class, 'viewQrCode']);
Route::post('/process-payment-service', [PaymentServiceController::class, 'ProcessPaymentService'])->name('process.payment.service');
Route::post('/process-payment-prescription', [PaymentPresController::class, 'ProcessPaymentPrescription'])->name('process.payment.prescription');
Route::post('/process-payment-patient', [PaymentPatientController::class, 'ProcessPaymentPatient'])->name('process.payment.patient');
Route::get('/print-invoice-prescription/{id}', [PaymentPresController::class, 'printInvoice']);
Route::get('/print-invoice-service/{id}', [PaymentServiceController::class, 'printInvoice']);
Route::get('/print-invoice-patient/{id}', [PaymentPatientController::class, 'printInvoice']);