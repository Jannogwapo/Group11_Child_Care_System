Route::get('/cities/{province}', function ($provinceId) {
    return App\Models\PhilippineCity::where('province_id', $provinceId)->get();
});

Route::get('/barangays/{city}', function ($cityId) {
    return App\Models\PhilippineBarangay::where('city_id', $cityId)->get();
}); 