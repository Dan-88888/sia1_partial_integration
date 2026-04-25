<button class="sidebar-link active" onclick="location.href='{{ route('dashboard') }}'">Dashboard</button>

<button class="sidebar-link" onclick="toggleSubmenu('transSub')">Transactions</button>
<div class="sidebar-submenu" id="transSub">
  <a class="sidebar-sublink" href="{{ route('dashboard.page', ['role' => 'student', 'slug' => 'change-password']) }}">Change Password</a>
  <a class="sidebar-sublink" href="{{ route('dashboard.page', ['role' => 'student', 'slug' => 'student-profile']) }}">Student Profile</a>
  <a class="sidebar-sublink" href="{{ route('student.transactions.pre_enlistment') }}">Pre-Enlistment</a>
  <a class="sidebar-sublink" href="{{ route('student.transactions.enrollment') }}">Enrollment</a>
  <a class="sidebar-sublink" href="{{ route('dashboard.page', ['role' => 'student', 'slug' => 'adding-and-dropping']) }}">Adding and Dropping</a>
  <a class="sidebar-sublink" href="{{ route('dashboard.page', ['role' => 'student', 'slug' => 'assessment']) }}">Assessment</a>
</div>

<button class="sidebar-link" onclick="toggleSubmenu('reportsSub')">Reports</button>
<div class="sidebar-submenu" id="reportsSub">
  <a class="sidebar-sublink" href="{{ route('student.reports.enrolled_subjects') }}">Enrolled Subjects</a>
  <a class="sidebar-sublink" href="{{ route('dashboard.page', ['role' => 'student', 'slug' => 'class-absences']) }}">Class Absences</a>
  <a class="sidebar-sublink" href="{{ route('student.reports.term_grades') }}">Term Grades</a>
  <a class="sidebar-sublink" href="{{ route('dashboard.page', ['role' => 'student', 'slug' => 'final-grades']) }}">Final Grades</a>
  <a class="sidebar-sublink" href="{{ route('dashboard.page', ['role' => 'student', 'slug' => 'gwa']) }}">GWA</a>
</div>

<button class="sidebar-link" onclick="toggleSubmenu('helpSub')">Help</button>
<div class="sidebar-submenu" id="helpSub">
  <a class="sidebar-sublink" href="{{ route('dashboard.page', ['role' => 'student', 'slug' => 'support']) }}">Support</a>
  <a class="sidebar-sublink" href="{{ route('dashboard.page', ['role' => 'student', 'slug' => 'about-us']) }}">About Us</a>
</div>
