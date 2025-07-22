<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ set_active(['home']) }}"> <a href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span></a> </li>
                <li class="list-divider"></li>
                <li class="submenu"> <a href="#"><i class="fas fa-calendar-check"></i> <span> Reservations </span>
                        <span class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['reservations/all']) }}" href="{{ route('form/allbooking') }}"> All
                                Reservations </a></li>
                        <li><a class="{{ set_active(['reservations/new']) }}" href="{{ route('form/booking/add') }}">
                                New Reservation </a></li>
                        <li><a href="#"> Today's Arrivals </a></li>
                        <li><a href="#"> Today's Departures </a></li>
                        <li><a href="#"> Reservation Calendar </a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="fas fa-concierge-bell"></i> <span> Front Desk </span>
                        <span class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a href="#"> Check-in </a></li>
                        <li><a href="#"> Check-out </a></li>
                        <li><a href="#"> In-House Guests </a></li>
                        <li><a href="#"> Walk-in Booking </a></li>
                        <li><a href="#"> Room Assignment </a></li>
                        <li><a href="#"> Guest Folio </a></li>
                        <li><a href="#"> Night Audit </a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="fas fa-broom"></i> <span> Housekeeping </span> <span
                            class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a href="#"> Room Status </a></li>
                        <li><a href="#"> Cleaning Schedule </a></li>
                        <li><a href="#"> Maintenance Requests </a></li>
                        <li><a href="#"> Housekeeping Reports </a></li>
                        <li><a href="#"> Lost & Found </a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="fas fa-user"></i> <span> Customers </span> <span
                            class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['form/allcustomers/page']) }}"
                                href="{{ route('form/allcustomers/page') }}"> All customers </a></li>
                        <li><a class="{{ request()->is('form/customer/edit/*') ? 'active' : '' }}"> Edit Customer </a>
                        </li>
                        <li><a class="{{ set_active(['form/addcustomer/page']) }}"
                                href="{{ route('form/addcustomer/page') }}"> Add Customer </a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="fas fa-key"></i> <span> Rooms </span> <span
                            class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['form/allrooms/page']) }}"
                                href="{{ route('form/allrooms/page') }}">All Rooms </a></li>
                        <li><a class="{{ request()->is('form/room/edit/*') ? 'active' : '' }}"> Edit Rooms </a></li>
                        <li><a class="{{ set_active(['form/addroom/page']) }}"
                                href="{{ route('form/addroom/page') }}"> Add Rooms </a></li>
                        <li><a class="{{ set_active(['form/addroom/page']) }}"
                                href="{{ route('roomtype.index') }}">Room Type </a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="fas fa-user"></i> <span> Employees </span> <span
                            class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['form/employee/list']) }}"
                                href="{{ route('form.employee.list') }}">All Employees</a></li>
                        <li>
                            <a class="{{ set_active(['form.employee.edit']) }}"
                                href="{{ route('form.employee.edit', ['id' => 1]) }}">Edit Employees</a>
                        </li>
                        <li><a class="{{ set_active(['form/employee/add']) }}"
                                href="{{ route('form.employee.add') }}">Add Employees</a></li>
                        <li>
                            <a class="{{ set_active(['leavetype']) }}" href="{{ route('leavetype.index') }}">
                                Type of Leaves
                            </a>
                        </li>
                        <li><a class="{{ set_active(['leave']) }}" href="{{ route('leave.index') }}">Leaves</a></li>
                        <li><a href="attendance.html">Attendance </a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="fas fa-file-invoice-dollar"></i> <span> Billing &
                            Invoices </span>
                        <span class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a href="#"> Guest Invoices </a></li>
                        <li><a href="#"> Payment Processing </a></li>
                        <li><a href="#"> Folio Management </a></li>
                        <li><a href="#"> Refunds </a></li>
                        <li><a href="#"> Tax Reports </a></li>
                        <li><a href="#"> Payment Methods </a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="fas fa-chart-bar"></i> <span> Reports </span>
                        <span class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a href="#"> Occupancy Report </a></li>
                        <li><a href="#"> Revenue Report </a></li>
                        <li><a href="#"> Guest History </a></li>
                        <li><a href="#"> Room Revenue </a></li>
                        <li><a href="#"> Daily Sales </a></li>
                        <li><a href="#"> Monthly Statistics </a></li>
                        <li><a href="#"> Guest Feedback </a></li>
                    </ul>
                </li>
                <li class="submenu"> <a href="#"><i class="fas fa-book"></i> <span> Payroll </span> <span
                            class="menu-arrow"></span></a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a href="salary.html">Employee Salary </a></li>
                        <li><a href="salary-veiw.html">Payslip </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-user-plus"></i>
                        <span> User Management </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="submenu_class" style="display: none;">
                        <li><a class="{{ set_active(['users/list/page']) }}"
                                href="{{ route('users/list/page') }}">All
                                User</a></li>
                        <li><a class="{{ set_active(['users/add/new']) }}" href="{{ route('users/add/new') }}">Add
                                User</a></li>
                        <li><a class="{{ set_active(['role']) }}" href="{{ route('role.index') }}">Role
                                Management</a>
                        </li>
                        <li><a href="">User Log Activity </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
