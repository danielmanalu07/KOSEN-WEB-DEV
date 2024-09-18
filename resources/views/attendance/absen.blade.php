<x-layout>
  <div class="row">
    <div class="col-lg-8 d-flex align-items-strech">
      <div class="card w-100">
        <div class="card-body">
          <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
            {{-- <div class="mb-3 mb-sm-0">
              <h5 class="card-title fw-semibold">Kelas</h5>
              <div class="row">
                @foreach ($kelases as $index => $kls)
                @php
                $gambarIndex = $index % 3 + 1;
                @endphp
                <div class="col mt-4 d-flex justify-content-center">
                  <article class="card__kelas rounded">
                    <a href="{{ 'absen/' . $kls->nama_kelas }}" class="card__link">
                      <div class="card__media">
                        <img src="{{ $kls->gambar ?? '../assets/images/blog/blog-img'. $gambarIndex .'.jpg' }}"
                          alt="Gambar Kelas">
                      </div>
                      <div class="card__header">
                        <h3 class="card__header-title">Kelas {{ $kls->nama_kelas }}</h3>
                        <p class="card__header-meta">{{ $kls->nama_guru }}</p>
                        <div class="card__header-icon">
                          <svg viewbox="0 0 28 25">
                            <path fill="#00000"
                              d="M13.145 2.13l1.94-1.867 12.178 12-12.178 12-1.94-1.867 8.931-8.8H.737V10.93h21.339z" />
                          </svg>
                        </div>
                      </div>
                    </a>
                  </article>
                </div>
                @endforeach
              </div>
            </div> --}}
          </div>
        </div>
      </div>
    </div>
    {{-- <div class="col-lg-4 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-4">
            <h5 class="card-title fw-semibold">Jadwal Mengajar</h5>
          </div>
          <ul class="timeline-widget mb-0 position-relative mb-n5">
            @foreach($jadwalMengajars as $jadwal)
            <li class="timeline-item d-flex position-relative overflow-hidden" data-time="{{ $jadwal->waktu }}">
              <div class="timeline-time mt-n1 text-muted flex-shrink-0 text-end">
                {{ \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->waktu)->format('H:i') }}
              </div>
              <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                <span class="timeline-badge flex-shrink-0 mt-2"></span>
                <span class="timeline-badge-border d-block flex-shrink-0"></span>
              </div>
              <div class="timeline-desc fs-3 text-dark mt-n1 fw-semibold">
                <a href="{{ 'absen/' . $jadwal->nama_kelas }}">{{ $jadwal->deskripsi }} di {{ $jadwal->nama_kelas }}</a>
              </div>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div> --}}
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const items = document.querySelectorAll('.timeline-item');
      items.forEach(item => {
        const dbTime = item.getAttribute('data-time');
        const dbDate = new Date();
        const [hours, minutes, seconds] = dbTime.split(':').map(Number);
        dbDate.setHours(hours, minutes, seconds, 0);
        const now = new Date();
        const diffInMinutes = (dbDate - now) / 1000 / 60;
        const badge = item.querySelector('.timeline-badge');
        const badgeBorder = item.querySelector('.timeline-badge-border');
        if (diffInMinutes <= 30) {
          console.log(diffInMinutes);
          badge.style.backgroundColor = 'red';
          badgeBorder.style.borderColor = 'red';
        } else if (diffInMinutes <= 60) {
          badge.style.backgroundColor = 'yellow';
          badgeBorder.style.borderColor = 'yellow';
        } else if (diffInMinutes <= 90) {
          badge.style.backgroundColor = 'lightblue';
          badgeBorder.style.borderColor = 'lightblue';
        } else {
          badge.style.backgroundColor = 'blue';
          badgeBorder.style.borderColor = 'blue';
        }
      });
    });
  </script>

</x-layout>