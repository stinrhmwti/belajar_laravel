@extends('layouts.app')
@section('title', 'Lapor Keluhan Kendaraan')

@section('content')
<style>
    /* Premium Brand Styling */
    .btn-outline-brand {
        color: #e5484d;
        border-color: #fca5a5;
        background: transparent;
    }
    .btn-outline-brand:hover {
        background-color: #e5484d;
        color: #fff;
        border-color: #e5484d;
    }
    .btn-check:checked + .btn-outline-brand {
        background-color: #e5484d;
        color: #fff;
        border-color: #e5484d;
        box-shadow: 0 4px 10px rgba(229, 72, 77, 0.25);
    }
    
    .btn-brand {
        background: linear-gradient(135deg, #e5484d 0%, #c92a30 100%);
        color: #fff;
        border: none;
        box-shadow: 0 4px 12px rgba(229, 72, 77, 0.2);
        transition: all 0.2s ease;
    }
    .btn-brand:hover {
        background: linear-gradient(135deg, #c92a30 0%, #b21e24 100%);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(229, 72, 77, 0.3);
    }
    .btn-brand:active {
        transform: translateY(0);
    }
    
    /* Live Camera Styles */
    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
        border-color: #cbd5e1 !important;
    }
    
    #camera_preview_wrapper {
        position: relative;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 4/3;
    }
    
    /* Scanner laser animation on preview and modal */
    .scanner-laser {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(180deg, rgba(34, 197, 94, 0) 0%, rgba(34, 197, 94, 1) 50%, rgba(34, 197, 94, 0) 100%);
        box-shadow: 0 0 10px #22c55e, 0 0 20px #22c55e;
        animation: scan 2s infinite ease-in-out;
        z-index: 10;
    }

    .scanner-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(180deg, rgba(34, 197, 94, 0) 0%, #22c55e 50%, rgba(34, 197, 94, 0) 100%);
        box-shadow: 0 0 8px #22c55e;
        animation: scan 1.8s infinite ease-in-out;
        z-index: 10;
    }
    
    @keyframes scan {
        0% { top: 0%; }
        50% { top: 100%; }
        100% { top: 0%; }
    }
    
    .btn-xs {
        padding: 4px 10px;
        font-size: 0.75rem;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:64px;height:64px;background:#fdecec;color:#e5484d;font-size:1.8rem;">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <h4 class="fw-bold" style="color:#0f1b3d;">Lapor Keluhan Kendaraan</h4>
            <p class="text-muted mb-0">Laporkan kendala yang dialami dengan armada Anda secara cepat dan akurat.</p>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" id="complaintForm">
                    @csrf
                    
                    <!-- Pilih Kendaraan -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-car-front"></i> Pilih Kendaraan</label>
                        <select name="vehicle_id" id="vehicle_select" class="form-select form-select-lg" required>
                            <option value="">-- Pilih Kendaraan --</option>
                            @foreach ($vehicles as $v)
                                <option value="{{ $v->id }}" @selected(request('vehicle_id') == $v->id)>{{ $v->plat_nomor }} - {{ $v->jenis_kendaraan }} ({{ $v->merek }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Kejadian -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-calendar-event"></i> Tanggal Kejadian</label>
                        <input type="date" name="tanggal" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- AI Scan Helper Box (Initially Hidden) -->
                    <div id="ai_scan_helper" class="card border-info-subtle bg-info bg-opacity-10 p-3 rounded-4 mb-3 d-none animate__animated animate__fadeIn">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-info-subtle text-info text-center" style="width: 44px; height: 44px; font-size: 1.3rem;">
                                <i class="bi bi-stars"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.88rem;">Analisis Kerusakan AI Aktif</h6>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Foto kerusakan terdeteksi! Gunakan simulasi AI untuk mendeteksi masalah & mengisi deskripsi otomatis.</p>
                            </div>
                            <button type="button" id="btn_ai_scan" class="btn btn-brand btn-sm px-3 py-2 shadow-sm d-flex align-items-center gap-1 text-nowrap" style="font-size: 0.8rem;">
                                <i class="bi bi-stars"></i> Pindai Foto (AI)
                            </button>
                        </div>
                    </div>

                    <!-- Ceritakan Kendalanya -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-semibold mb-0"><i class="bi bi-chat-left-text"></i> Ceritakan Kendalanya</label>
                            
                            <!-- Template dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-xs btn-outline-secondary dropdown-toggle py-1 px-2" type="button" id="templateDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem; border-radius: 6px;">
                                    <i class="bi bi-list-task"></i> Templat Cepat
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="templateDropdown" style="font-size: 0.8rem; border-radius: 10px;">
                                    <li><h6 class="dropdown-header">Pilih Masalah Umum</h6></li>
                                    <li><a class="dropdown-item issue-template-item" href="#" data-text="Rem terasa kurang pakem dan pedal rem terasa ambles saat diinjak di jalanan menurun. Terdengar juga bunyi decit gesekan besi dari roda depan saat pengereman mendadak.">Rem Kurang Pakem &amp; Berdecit</a></li>
                                    <li><a class="dropdown-item issue-template-item" href="#" data-text="Ban bagian belakang luar sebelah kanan terlihat sudah sangat tipis (gundul) dan terdapat sobekan kecil di dinding samping luar ban. Berisiko pecah jika membawa muatan berat di jalan tol.">Ban Gundul &amp; Dinding Sobek</a></li>
                                    <li><a class="dropdown-item issue-template-item" href="#" data-text="Indikator suhu temperatur mesin di panel dasbor naik melebihi batas setengah (mendekati zona merah) saat berkendara di siang hari macet, dan keluar sedikit uap dari kap depan.">Mesin Overheat / Panas</a></li>
                                    <li><a class="dropdown-item issue-template-item" href="#" data-text="Suhu AC di kabin mobil sama sekali tidak terasa dingin dan hanya mengembuskan angin hangat biasa. Tercium pula bau kurang sedap saat pertama kali tombol AC diaktifkan.">AC Tidak Dingin (Hanya Angin)</a></li>
                                    <li><a class="dropdown-item issue-template-item" href="#" data-text="Pedal kopling terasa sangat keras saat diinjak dan transmisi gigi seringkali tersendat / sulit dipindahkan, terutama dari gigi 1 menuju gigi 2 saat kondisi mesin masih dingin.">Pedal Kopling Keras &amp; Slip</a></li>
                                    <li><a class="dropdown-item issue-template-item" href="#" data-text="Lampu utama (headlight) sebelah kiri mati total. Selain itu, lampu sein belakang kanan berkedip sangat cepat yang menandakan adanya kerusakan bohlam kelistrikan.">Lampu Utama Mati / Sein Rusak</a></li>
                                </ul>
                            </div>
                        </div>
                        <textarea name="keluhan" id="keluhan" class="form-control" rows="4" placeholder="Contoh: Rem terasa kurang pakem saat digunakan di jalan menurun, mohon dicek segera" required></textarea>
                        <div class="form-text">Semakin detail, semakin cepat teknisi memahami masalahnya.</div>
                    </div>

                    <!-- Upload / Ambil Foto Kerusakan (Enhanced with webcam capture) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-camera-fill"></i> Foto Kerusakan (Sangat Disarankan)</span>
                            <span class="badge bg-light text-secondary border">Pilih Metode</span>
                        </label>
                        
                        <!-- Toggle Buttons for upload vs camera -->
                        <div class="btn-group w-100 mb-3" role="group" aria-label="Input Method">
                            <input type="radio" class="btn-check" name="photo_method" id="method_upload" autocomplete="off" checked>
                            <label class="btn btn-outline-brand btn-sm d-flex align-items-center justify-content-center gap-1 py-2" for="method_upload">
                                <i class="bi bi-upload"></i> Unggah File Gambar
                            </label>

                            <input type="radio" class="btn-check" name="photo_method" id="method_camera" autocomplete="off">
                            <label class="btn btn-outline-brand btn-sm d-flex align-items-center justify-content-center gap-1 py-2" for="method_camera">
                                <i class="bi bi-webcam"></i> Ambil via Kamera Live
                            </label>
                        </div>

                        <!-- Upload File Input Container -->
                        <div id="upload_container" class="input-method-section">
                            <input type="file" name="foto_kerusakan" id="foto_kerusakan_file" class="form-control form-control-lg" accept="image/*">
                            <div class="form-text">Format gambar (JPG, PNG, JPEG), Maks 10MB.</div>
                        </div>

                        <!-- Camera Capture Interface Container -->
                        <div id="camera_container" class="input-method-section d-none">
                            <div class="card bg-light border-dashed p-3 text-center rounded-3 position-relative overflow-hidden" style="min-height: 250px;">
                                
                                <!-- Camera Controls -->
                                <div id="camera_controls" class="mb-3">
                                    <div class="row g-2 justify-content-center">
                                        <div class="col-sm-8">
                                            <select id="camera_select" class="form-select form-select-sm shadow-none" style="border-radius: 8px;">
                                                <option value="">-- Mendeteksi Kamera... --</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" id="btn_toggle_camera" class="btn btn-sm btn-brand w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: 8px;">
                                                <i class="bi bi-play-circle"></i> Buka Kamera
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Camera Video Preview -->
                                <div id="camera_preview_wrapper" class="d-none position-relative bg-black rounded-3 overflow-hidden mb-3">
                                    <video id="camera_video" autoplay playsinline class="w-100 h-100" style="object-fit: cover;"></video>
                                    
                                    <!-- Scanning overlay line -->
                                    <div id="scanner_laser" class="scanner-laser d-none"></div>

                                    <div class="position-absolute bottom-0 start-50 translate-middle-x pb-3 z-3 w-100 px-3 d-flex gap-2 justify-content-center">
                                        <button type="button" id="btn_capture_photo" class="btn btn-danger btn-sm px-4 py-2 rounded-pill shadow-sm d-flex align-items-center gap-1">
                                            <i class="bi bi-camera"></i> Jepret Foto
                                        </button>
                                        <button type="button" id="btn_stop_camera" class="btn btn-secondary btn-sm px-3 py-2 rounded-pill shadow-sm">
                                            Batal
                                        </button>
                                    </div>
                                </div>

                                <!-- Captured Image Preview (Camera) -->
                                <div id="camera_captured_preview" class="d-none">
                                    <div class="position-relative d-inline-block border rounded-3 p-1 bg-white mb-2" style="max-width: 100%;">
                                        <img id="camera_captured_img" src="" alt="Jepretan Kamera" class="img-fluid rounded-2" style="max-height: 250px;">
                                        <button type="button" id="btn_remove_captured" class="position-absolute btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center" style="top: -10px; right: -10px; width: 26px; height: 26px; padding: 0;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div class="text-success fw-semibold mb-2" style="font-size: 0.85rem;"><i class="bi bi-check-circle-fill"></i> Foto berhasil diambil!</div>
                                </div>

                                <!-- Placeholder when camera is inactive -->
                                <div id="camera_placeholder" class="py-4 text-secondary">
                                    <i class="bi bi-camera-video text-muted" style="font-size: 3rem;"></i>
                                    <p class="mb-0 mt-2" style="font-size: 0.85rem;">Kamera belum aktif. Klik <strong>Buka Kamera</strong> untuk memulai.</p>
                                </div>
                            </div>
                            
                            <!-- Hidden input for base64 photo -->
                            <input type="hidden" name="foto_kamera_base64" id="foto_kamera_base64">
                        </div>
                    </div>

                    <!-- Upload Video Kerusakan -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><i class="bi bi-file-earmark-play-fill"></i> Upload Video Kerusakan (Opsional)</label>
                        <input type="file" name="video_kerusakan" class="form-control" accept="video/*">
                        <div class="form-text">Contoh: Rekaman suara mesin yang tidak wajar. Maks 50MB.</div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions-mobile d-flex gap-2">
                        <button type="submit" class="btn btn-brand btn-lg flex-fill"><i class="bi bi-send"></i> Kirim Laporan</button>
                        <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary btn-lg">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-3">
            <small class="text-muted"><i class="bi bi-info-circle"></i> Laporan Anda akan langsung terlihat oleh Admin dan Teknisi</small>
        </div>
    </div>
</div>

<!-- Modal Simulasi Pemindaian AI -->
<div class="modal fade" id="aiScanModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #ffffff;">
            <div class="modal-body p-4 text-center">
                <!-- Visual scanner wrapper -->
                <div class="position-relative mx-auto rounded-4 overflow-hidden mb-4 shadow-sm bg-dark" style="width: 280px; height: 210px;">
                    <img id="ai_scan_img_preview" src="" alt="Scanning Preview" class="w-100 h-100 object-fit-cover" style="opacity: 0.7;">
                    <!-- Scanner sweep animation line -->
                    <div class="scanner-line"></div>
                </div>

                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-center gap-2">
                    <span class="spinner-border spinner-border-sm text-danger" role="status" aria-hidden="true"></span>
                    <span>Pemindaian Kerusakan AI...</span>
                </h5>
                <p class="text-muted px-2" style="font-size: 0.85rem;" id="ai_scan_status_text">Menghubungkan ke modul AI deteksi kendaraan...</p>

                <!-- Micro progress indicators -->
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <span class="badge rounded-pill bg-danger text-white id-step-badge" id="badge_step_1">Koneksi</span>
                    <span class="badge rounded-pill bg-light border text-secondary id-step-badge" id="badge_step_2">Deteksi</span>
                    <span class="badge rounded-pill bg-light border text-secondary id-step-badge" id="badge_step_3">Analisis</span>
                    <span class="badge rounded-pill bg-light border text-secondary id-step-badge" id="badge_step_4">Drafting</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        let stream = null;
        let currentFacingMode = 'environment'; // default to back camera
        let activeDevices = [];

        // 1. Toggle Input Method (Upload vs Camera)
        $('input[name="photo_method"]').on('change', function () {
            if ($('#method_upload').is(':checked')) {
                $('#upload_container').removeClass('d-none');
                $('#camera_container').addClass('d-none');
                stopCamera();
                checkPhotoAvailable();
            } else {
                $('#upload_container').addClass('d-none');
                $('#camera_container').removeClass('d-none');
                initCameraDevices();
                checkPhotoAvailable();
            }
        });

        // 2. Load available camera devices
        function initCameraDevices() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                alert('Fitur kamera tidak didukung di browser ini.');
                return;
            }

            navigator.mediaDevices.enumerateDevices()
                .then(function (devices) {
                    let videoDevices = devices.filter(d => d.kind === 'videoinput');
                    activeDevices = videoDevices;
                    let $select = $('#camera_select').empty();
                    
                    if (videoDevices.length === 0) {
                        $select.append('<option value="">Tidak ada kamera terdeteksi</option>');
                        return;
                    }

                    videoDevices.forEach((device, index) => {
                        let label = device.label || `Kamera ${index + 1}`;
                        $select.append(`<option value="${device.deviceId}">${label}</option>`);
                    });
                })
                .catch(function (err) {
                    console.error('Error listing camera devices:', err);
                });
        }

        // 3. Toggle Camera Stream (Open/Close)
        $('#btn_toggle_camera').on('click', function () {
            if (stream) {
                stopCamera();
            } else {
                startCamera();
            }
        });

        $('#btn_stop_camera').on('click', function () {
            stopCamera();
        });

        function startCamera() {
            let deviceId = $('#camera_select').val();
            let constraints = {
                video: deviceId ? { deviceId: { exact: deviceId } } : { facingMode: currentFacingMode }
            };

            navigator.mediaDevices.getUserMedia(constraints)
                .then(function (mediaStream) {
                    stream = mediaStream;
                    let video = document.getElementById('camera_video');
                    video.srcObject = stream;
                    video.play();
                    
                    $('#camera_preview_wrapper').removeClass('d-none');
                    $('#camera_placeholder').addClass('d-none');
                    $('#btn_toggle_camera').html('<i class="bi bi-stop-circle"></i> Tutup Kamera').removeClass('btn-brand').addClass('btn-secondary');
                    $('#scanner_laser').removeClass('d-none');
                })
                .catch(function (err) {
                    console.error('Gagal mengakses kamera:', err);
                    alert('Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan.');
                });
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            $('#camera_preview_wrapper').addClass('d-none');
            $('#camera_placeholder').removeClass('d-none');
            $('#btn_toggle_camera').html('<i class="bi bi-play-circle"></i> Buka Kamera').removeClass('btn-secondary').addClass('btn-brand');
            $('#scanner_laser').addClass('d-none');
        }

        // 4. Capture Photo from live stream
        $('#btn_capture_photo').on('click', function () {
            let video = document.getElementById('camera_video');
            let canvas = document.createElement('canvas');
            
            // Set canvas dimensions to match video stream
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            
            let ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert to base64 jpeg
            let dataUrl = canvas.toDataURL('image/jpeg', 0.85);
            
            // Set base64 value to hidden input
            $('#foto_kamera_base64').val(dataUrl);
            
            // Show preview
            $('#camera_captured_img').attr('src', dataUrl);
            $('#camera_captured_preview').removeClass('d-none');
            
            // Stop camera after capture
            stopCamera();
            checkPhotoAvailable();
        });

        // 5. Remove captured photo
        $('#btn_remove_captured').on('click', function () {
            $('#foto_kamera_base64').val('');
            $('#camera_captured_preview').addClass('d-none');
            checkPhotoAvailable();
        });

        // 6. Monitor File upload file change
        $('#foto_kerusakan_file').on('change', function () {
            checkPhotoAvailable();
        });

        // 7. Check if a photo (upload or camera) is available to show AI Scan button
        let activePhotoData = null; // store current base64 or file URL for AI preview modal

        function checkPhotoAvailable() {
            let hasUpload = $('#foto_kerusakan_file')[0].files && $('#foto_kerusakan_file')[0].files[0];
            let hasCamera = $('#foto_kamera_base64').val() !== '';

            if (hasUpload && $('#method_upload').is(':checked')) {
                // Read file to base64 for AI scan preview
                let reader = new FileReader();
                reader.onload = function (e) {
                    activePhotoData = e.target.result;
                    $('#ai_scan_helper').removeClass('d-none').addClass('animate__animated animate__fadeIn');
                };
                reader.readAsDataURL($('#foto_kerusakan_file')[0].files[0]);
            } else if (hasCamera && $('#method_camera').is(':checked')) {
                activePhotoData = $('#foto_kamera_base64').val();
                $('#ai_scan_helper').removeClass('d-none').addClass('animate__animated animate__fadeIn');
            } else {
                activePhotoData = null;
                $('#ai_scan_helper').addClass('d-none');
            }
        }

        // 8. Template selection click handler
        $('.issue-template-item').on('click', function (e) {
            e.preventDefault();
            let text = $(this).data('text');
            $('#keluhan').val(text);
        });

        // 9. AI Scan simulated progress
        $('#btn_ai_scan').on('click', function () {
            if (!activePhotoData) return;

            // Set modal preview image
            $('#ai_scan_img_preview').attr('src', activePhotoData);
            
            // Reset modal progress steps UI
            $('.id-step-badge').removeClass('bg-danger text-white').addClass('bg-light border text-secondary');
            $('#badge_step_1').removeClass('bg-light border text-secondary').addClass('bg-danger text-white');

            // Open Modal
            let aiModal = new bootstrap.Modal(document.getElementById('aiScanModal'));
            aiModal.show();

            // Set step sequence
            setTimeout(() => {
                $('#ai_scan_status_text').text('Mendeteksi tipe bodi & komponen kendaraan...');
                $('.id-step-badge').removeClass('bg-danger text-white').addClass('bg-light border text-secondary');
                $('#badge_step_2').removeClass('bg-light border text-secondary').addClass('bg-danger text-white');
            }, 700);

            setTimeout(() => {
                $('#ai_scan_status_text').text('Menganalisis area kerusakan & kecocokan pola...');
                $('.id-step-badge').removeClass('bg-danger text-white').addClass('bg-light border text-secondary');
                $('#badge_step_3').removeClass('bg-light border text-secondary').addClass('bg-danger text-white');
            }, 1400);

            setTimeout(() => {
                $('#ai_scan_status_text').text('Menyusun teks laporan keluhan otomatis...');
                $('.id-step-badge').removeClass('bg-danger text-white').addClass('bg-light border text-secondary');
                $('#badge_step_4').removeClass('bg-light border text-secondary').addClass('bg-danger text-white');
            }, 2100);

            setTimeout(() => {
                // Auto-fill keluhan description
                generateAiDescription();
                
                // Close modal
                aiModal.hide();
            }, 2800);
        });

        // 10. AI Auto-fill generation logic based on selected vehicle
        function generateAiDescription() {
            let vehicleText = $('#vehicle_select option:selected').text().toLowerCase();
            let isTruck = vehicleText.includes('truk') || vehicleText.includes('boks') || vehicleText.includes('fuso') || vehicleText.includes('pickup') || vehicleText.includes('pick up') || vehicleText.includes('traga') || vehicleText.includes('l300') || vehicleText.includes('box');
            
            let truckIssues = [
                "Terdeteksi adanya kebocoran angin pada sistem pengereman utama. Saat pedal rem ditekan, terdengar bunyi mendesis yang cukup keras di bagian roda belakang sebelah kiri, dan tekanan tabung angin cepat menurun di bawah normal. Mohon segera dicek pipa saluran udara rem.",
                "Pemeriksaan ban bagian belakang luar kanan menunjukkan kondisi tapak ban yang sudah aus tidak merata dan dinding ban samping terkelupas/sobek sepanjang 3cm. Sangat berisiko pecah ban jika kendaraan mengangkut muatan logistik berat.",
                "Indikator temperatur mesin menunjukkan kenaikan suhu di atas normal (overheat) saat mendaki tanjakan curam dalam keadaan bermuatan. Ada juga suara ketukan mendengung tidak wajar dari area kompresor dan pompa radiator di kap mesin."
            ];

            let passengerIssues = [
                "AC pada kabin bagian tengah dan belakang mati total dan hanya mengeluarkan embusan angin panas, kemungkinan terdapat kebocoran freon atau kerusakan pada magnetic clutch kompresor AC. Suara mesin juga tersendat saat AC dinyalakan.",
                "Rem depan bergetar dan mengeluarkan bunyi decit besi yang tajam saat pedal rem diinjak pada kecepatan rendah. Kemungkinan kampas rem sudah sangat tipis atau piringan cakram mengalami keausan bergelombang.",
                "Pedal kopling terasa sangat dalam dan keras saat ditekan, serta terjadi gejala slip kopling ketika mobil berakselerasi di gigi 2 dan 3. Tarikan mobil menjadi loyo dan boros bahan bakar."
            ];

            let selectedIssue = "";
            if (isTruck) {
                let index = Math.floor(Math.random() * truckIssues.length);
                selectedIssue = truckIssues[index];
            } else {
                let index = Math.floor(Math.random() * passengerIssues.length);
                selectedIssue = passengerIssues[index];
            }

            // Write value to textarea
            $('#keluhan').val(selectedIssue);
            
            // Show dynamic success banner above textarea if needed, or simply style outline green
            $('#keluhan').addClass('is-valid');
            setTimeout(() => {
                $('#keluhan').removeClass('is-valid');
            }, 4000);
        }
    });
</script>
@endsection