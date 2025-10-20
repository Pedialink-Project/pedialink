@extends('layout/portal')

@section('title')
{{$child['name']}}'s Details
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/parent/my-child-details.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection


@section('content')

<main class="container">

    <div class="left-col">

        <c-card class="card info-card">

            <?php $words = explode(" ", $child["name"]);
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
            ?>

            <div class="card-header">
                <div class="header__left">
                    <div class="profile-pic">
                        @if ($child['image'])
                        <img src="{{ asset('images/' . $child['image']) }}" alt="Profile Picture">
                        @else
                        <div class="initials">{{ $initials }}</div>
                        @endif
                    </div>
                    <div class="child-info">
                        <h3 class="child-name">{{ $child['name'] }}</h3>
                        <p class="nickname">{{ $child['nickname'] }}</p>
                    </div>
                </div>
            </div>

            <div class="card-body info-body">

                <div class="section">

                    <span class="section-title">Quick Stats</span>


                    <c-modal.viewcard>

                        <c-modal.viewitem icon="{{ asset('assets/icons/calendar-03.svg') }}" title="Date of Birth"
                            info="{{ $child['dob'] }}" />
                        <c-modal.viewitem icon="{{ asset('assets/icons/baby-01.svg') }}" title="Age"
                            info="{{ $child['age'] }}" />

                        <c-modal.viewitem icon="{{ asset('assets/icons/blood-type.svg') }}" title="Blood Type"
                            info="{{ $child['blood'] }}" />
                        <c-modal.viewitem icon="{{ asset('assets/icons/body-weight.svg') }}" title="Weight"
                            info="{{ $child['weight'] }} kg" />
                        <c-modal.viewitem icon="{{ asset('assets/icons/ruler.svg') }}" title="Height"
                            info="{{ $child['height'] }} cm" />
                        <c-modal.viewitem icon="{{ asset('assets/icons/chart.svg') }}" title="BMI"
                            info="{{ $child['bmi'] }}" />
                        <c-modal.viewitem icon="{{ asset('assets/icons/doctor.svg') }}" title="Assigned PHM"
                            info="{{ $child['phm'] }}" />



                    </c-modal.viewcard>

                </div>
                <div class="section">

                    <span class="section-title">Emergency Contact</span>


                    <c-modal.viewcard>

                        <c-modal.viewitem icon="{{ asset('assets/icons/mother.svg') }}" title="Parent"
                            info="{{ $child['parent_name'] }}" />
                        <c-modal.viewitem icon="{{ asset('assets/icons/call.svg') }}" title="Phone Number"
                            info="{{ $child['parent_phone'] }}" />
                        <c-modal.viewitem icon="{{ asset('assets/icons/mail-01.svg') }}" title="Email Address"
                            info="{{ $child['parent_email'] }}" />




                    </c-modal.viewcard>

                </div>

            </div>


        </c-card>



        <c-card class="card vaccine-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Vaccination Timeline</span>
                    <span class="card-subtitle">View {{$child['name']}}'s Vaccination timeline</span>
                </div>
                <c-button variant="secondary" size="sm">View All</c-button>
            </div>
            <hr class="divider">
            <div class="card-body">
                <!-- Single vaccine row  -->
                <div class="row vaccine">
                    <div class="primary-details">
                        <div class="name">Hepatitis B</div>
                        <div class="sub-details">
                            <div class="sub-name">At Birth</div>
                        </div>
                    </div>

                    <c-badge type="green">Completed</c-badge>

                    <div class="secondary-details">
                        <div class="date">2023-10-22</div>
                    </div>

                </div>
                <!-- Repeatable rows for other vaccines -->
                <div class="row vaccine">
                    <div class="primary-details">
                        <div class="name">MMR</div>
                        <div class="sub-details">
                            <div class="sub-name">At Birth</div>
                        </div>
                    </div>

                    <c-badge type="green">Completed</c-badge>

                    <div class="secondary-details">
                        <div class="date">2023-10-22</div>
                    </div>

                </div>
                <div class="row vaccine">
                    <div class="primary-details">
                        <div class="name">BCG</div>
                        <div class="sub-details">
                            <div class="sub-name">At Birth</div>
                        </div>
                    </div>

                    <c-badge type="red">Overdue</c-badge>

                    <div class="secondary-details">
                        <div class="date">2023-10-22</div>
                    </div>
                </div>
                <div class="row vaccine">
                    <div class="primary-details">
                        <div class="name">OPV 0</div>
                        <div class="sub-details">
                            <div class="sub-name">At 06 Months</div>
                        </div>
                    </div>

                    <c-badge type="purple">Upcoming</c-badge>

                    <div class="secondary-details">
                        <div class="date">2023-10-22</div>
                    </div>
                </div>
            </div>

            <div class="row vaccine">
                <div class="primary-details">
                    <div class="name">OPV 1</div>
                    <div class="sub-details">
                        <div class="sub-name">At 09 Months</div>
                    </div>
                </div>

                <c-badge type="purple">Upcoming</c-badge>

                <div class="secondary-details">
                    <div class="date">2023-10-22</div>
                </div>
            </div>



        </c-card>

        <!-- Height Chart Card -->
        <c-card class="card height-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Height Tracking</span>
                    <span class="card-subtitle">Track {{$child['name']}}'s Height over time</span>
                </div>
                <c-button variant="secondary" size="sm">View All</c-button>

            </div>
            <hr class="divider">
            <div class="card-body">
                <canvas id="heightChart">

                </canvas>
            </div>
        </c-card>



    </div>



    <div class="right-col">
        <c-card class="card appoinment-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Appoinments Timeline</span>
                    <span class="card-subtitle">Appointments details of {{$child['name']}}</span>
                </div>
                <c-button variant="secondary" size="sm">View All</c-button>
            </div>
            <hr class="divider">
            <div class="card-body">
                <!-- Single appointment row  -->
                <div class="row appoinment">
                    <div class="primary-details">
                        <div class="name">Routine Checkup</div>
                        <div class="sub-details">
                            <!--Doctor Icon-->
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.32468 4.66309L8.35218 4.54309C8.40468 4.32009 8.49068 3.99209 8.66818 3.66909C8.84918 3.34009 9.12968 3.00659 9.56968 2.78709C10.0082 2.56859 10.5577 2.48709 11.2372 2.57709C11.9872 2.67709 13.4937 2.92509 14.8247 3.60209C16.1632 4.28309 17.4172 5.45459 17.4172 7.40559C17.4172 8.41359 17.0272 9.49509 16.6492 10.2001C16.4677 10.5391 16.2522 10.8631 16.0362 11.0376C15.9867 11.0776 15.9162 11.1276 15.8287 11.1626C15.5887 11.9514 15.1109 12.6468 14.4607 13.1537C13.8104 13.6607 13.0195 13.9544 12.196 13.9948C11.3725 14.0352 10.5566 13.8202 9.85994 13.3793C9.16323 12.9384 8.61971 12.2931 8.30368 11.5316L8.29168 11.5336L8.11418 11.3181L8.11368 11.3171L8.11268 11.3161L8.11018 11.3126L8.10168 11.3026C8.05892 11.2493 8.01774 11.1948 7.97818 11.1391C7.87046 10.9892 7.76871 10.835 7.67318 10.6771C7.43818 10.2881 7.15468 9.73409 6.97318 9.08759C6.79218 8.44109 6.70918 7.68309 6.89918 6.90109C7.08418 6.14009 7.52068 5.39109 8.31118 4.72209L8.32468 4.66309ZM9.16468 10.9821C9.38153 11.6057 9.79796 12.1404 10.3495 12.5034C10.901 12.8664 11.5568 13.0374 12.2153 12.99C12.8739 12.9426 13.4984 12.6793 13.9922 12.241C14.486 11.8027 14.8214 11.2138 14.9467 10.5656L15.0032 10.5846C15.001 10.5563 15 10.528 15.0002 10.4996C15.0002 9.67209 14.8852 9.09459 14.7782 8.73459C14.7572 8.66455 14.734 8.59518 14.7087 8.52659L14.6882 8.52759H14.6682C14.2943 8.53561 13.9205 8.50714 13.5522 8.44259C12.6937 8.29759 11.5312 7.93559 10.1622 7.09709C10.1322 7.16409 10.1022 7.23959 10.0722 7.32359C9.97418 7.60109 9.89368 7.93059 9.82418 8.27859C9.76268 8.58509 9.71218 8.89359 9.66418 9.18259L9.64568 9.29659C9.59418 9.60659 9.54268 9.91109 9.48268 10.1316C9.37768 10.5146 9.27068 10.7866 9.16468 10.9816M8.46468 10.0501C8.23907 9.66233 8.06175 9.24842 7.93668 8.81759C7.78718 8.28459 7.73218 7.70759 7.87118 7.13709C8.00618 6.58209 8.33268 5.99859 9.00418 5.44559C9.12518 5.36359 9.18018 5.25359 9.19518 5.22409V5.22359C9.2212 5.16943 9.24228 5.11303 9.25818 5.05509C9.27418 4.99809 9.29168 4.92009 9.30818 4.85009L9.32568 4.77159C9.37318 4.57009 9.43468 4.35059 9.54468 4.15009C9.65168 3.95609 9.79818 3.79009 10.0162 3.68159C10.2352 3.57209 10.5742 3.49709 11.1052 3.56809C11.8317 3.66459 13.1982 3.89609 14.3712 4.49309C15.5362 5.08559 16.4172 5.98259 16.4172 7.40509C16.4172 8.06009 16.1972 8.80509 15.9322 9.39259C15.8822 9.00959 15.8102 8.69659 15.7362 8.44859C15.684 8.27152 15.6197 8.09827 15.5437 7.93009C15.5145 7.86798 15.4828 7.80709 15.4487 7.74759L15.4402 7.73409L15.4372 7.72909L15.4357 7.72659L15.4347 7.72509L15.2497 7.43809L14.9187 7.50459L14.9117 7.50609L14.8632 7.51259C14.7888 7.52094 14.714 7.52594 14.6392 7.52759C14.3309 7.5335 14.0228 7.50939 13.7192 7.45559C12.9127 7.31959 11.7277 6.94909 10.2882 5.99059L9.93818 5.75709L9.64868 6.06209C9.41018 6.31309 9.24818 6.65559 9.12968 6.98909C9.00868 7.33159 8.91718 7.71409 8.84368 8.08109C8.7827 8.3934 8.72736 8.70678 8.67768 9.02109L8.65868 9.13309C8.60518 9.45859 8.56168 9.70709 8.51768 9.86809C8.49968 9.93442 8.48201 9.99509 8.46468 10.0501Z"
                                    fill="#71717A" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.957 14.4274C8.851 14.2164 8.7205 13.9559 8.5 14.0064C5.798 14.6214 3 16.3969 3 18.2849V20.9999H21V18.2849C21 16.7979 19.264 15.3804 17.2065 14.5449L17.204 14.5399L17.197 14.5264L17.1805 14.5344C16.634 14.3144 16.065 14.1344 15.5 14.0064C15.2485 13.9489 14.9885 14.2949 14.875 14.5114H9L8.957 14.4274ZM15.7015 15.0874C15.9202 15.148 16.137 15.2159 16.352 15.2909C16.358 15.4619 16.345 15.6639 16.317 15.8699C16.2887 16.0832 16.2432 16.2939 16.181 16.4999H15.5C15.4072 16.4999 15.3162 16.5258 15.2372 16.5746C15.1583 16.6235 15.0945 16.6933 15.053 16.7764L14.553 17.7764C14.5182 17.8457 14.5001 17.9223 14.5 17.9999V18.9999C14.5 19.1325 14.5527 19.2596 14.6464 19.3534C14.7402 19.4472 14.8674 19.4999 15 19.4999H16V18.4999H15.5V18.1179L15.809 17.4999H17.191L17.5 18.1179V18.4999H17V19.4999H18C18.1326 19.4999 18.2598 19.4472 18.3536 19.3534C18.4473 19.2596 18.5 19.1325 18.5 18.9999V17.9999C18.4999 17.9223 18.4818 17.8457 18.447 17.7764L17.947 16.7764C17.9055 16.6933 17.8417 16.6235 17.7628 16.5746C17.6838 16.5258 17.5928 16.4999 17.5 16.4999H17.217C17.2781 16.2351 17.3196 15.9662 17.341 15.6954C17.8285 15.9259 18.2815 16.1904 18.674 16.4764C19.635 17.1774 20 17.8334 20 18.2849V19.9999H4V18.2849C4 17.8334 4.365 17.1774 5.326 16.4764C5.809 16.1239 6.3855 15.8049 7.0035 15.5409C7.02076 15.9393 7.0847 16.3343 7.194 16.7179L7.198 16.7319C6.89908 16.921 6.67661 17.2096 6.56987 17.5468C6.46313 17.8841 6.47899 18.2482 6.61466 18.5748C6.75033 18.9015 6.99707 19.1697 7.3113 19.3321C7.62554 19.4945 7.98705 19.5406 8.33198 19.4623C8.67692 19.384 8.98309 19.1863 9.19642 18.9042C9.40975 18.622 9.51651 18.2736 9.49785 17.9203C9.47919 17.5671 9.33631 17.2319 9.09443 16.9738C8.85256 16.7157 8.52727 16.5514 8.176 16.5099L8.154 16.4379C8.10114 16.2516 8.06187 16.0618 8.0365 15.8699C8.00847 15.676 7.99676 15.4802 8.0015 15.2844C8.00317 15.2424 8.00567 15.205 8.009 15.1724C8.069 15.1537 8.129 15.1359 8.189 15.1189L8.3965 15.5119H15.4785L15.7015 15.0874ZM8 18.5079C8.269 18.5079 8.5 18.2879 8.5 18.0004C8.5 17.7134 8.269 17.4929 8 17.4929C7.731 17.4929 7.5 17.7129 7.5 18.0004C7.5 18.2874 7.731 18.5079 8 18.5079Z"
                                    fill="#71717A" />
                            </svg>
                            <div class="sub-name">Dr.John</div>
                        </div>
                    </div>

                    <c-badge type="green">Completed</c-badge>

                    <div class="secondary-details">
                        <div class="date">2025-06-08</div>
                        <c-badge type="primary">11.30 AM</c-badge>
                    </div>

                </div>
                <!-- Repeatable rows for other appoinments -->
                <div class="row appoinment">
                    <div class="primary-details">
                        <div class="name">Routine Checkup</div>
                        <div class="sub-details">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.32468 4.66309L8.35218 4.54309C8.40468 4.32009 8.49068 3.99209 8.66818 3.66909C8.84918 3.34009 9.12968 3.00659 9.56968 2.78709C10.0082 2.56859 10.5577 2.48709 11.2372 2.57709C11.9872 2.67709 13.4937 2.92509 14.8247 3.60209C16.1632 4.28309 17.4172 5.45459 17.4172 7.40559C17.4172 8.41359 17.0272 9.49509 16.6492 10.2001C16.4677 10.5391 16.2522 10.8631 16.0362 11.0376C15.9867 11.0776 15.9162 11.1276 15.8287 11.1626C15.5887 11.9514 15.1109 12.6468 14.4607 13.1537C13.8104 13.6607 13.0195 13.9544 12.196 13.9948C11.3725 14.0352 10.5566 13.8202 9.85994 13.3793C9.16323 12.9384 8.61971 12.2931 8.30368 11.5316L8.29168 11.5336L8.11418 11.3181L8.11368 11.3171L8.11268 11.3161L8.11018 11.3126L8.10168 11.3026C8.05892 11.2493 8.01774 11.1948 7.97818 11.1391C7.87046 10.9892 7.76871 10.835 7.67318 10.6771C7.43818 10.2881 7.15468 9.73409 6.97318 9.08759C6.79218 8.44109 6.70918 7.68309 6.89918 6.90109C7.08418 6.14009 7.52068 5.39109 8.31118 4.72209L8.32468 4.66309ZM9.16468 10.9821C9.38153 11.6057 9.79796 12.1404 10.3495 12.5034C10.901 12.8664 11.5568 13.0374 12.2153 12.99C12.8739 12.9426 13.4984 12.6793 13.9922 12.241C14.486 11.8027 14.8214 11.2138 14.9467 10.5656L15.0032 10.5846C15.001 10.5563 15 10.528 15.0002 10.4996C15.0002 9.67209 14.8852 9.09459 14.7782 8.73459C14.7572 8.66455 14.734 8.59518 14.7087 8.52659L14.6882 8.52759H14.6682C14.2943 8.53561 13.9205 8.50714 13.5522 8.44259C12.6937 8.29759 11.5312 7.93559 10.1622 7.09709C10.1322 7.16409 10.1022 7.23959 10.0722 7.32359C9.97418 7.60109 9.89368 7.93059 9.82418 8.27859C9.76268 8.58509 9.71218 8.89359 9.66418 9.18259L9.64568 9.29659C9.59418 9.60659 9.54268 9.91109 9.48268 10.1316C9.37768 10.5146 9.27068 10.7866 9.16468 10.9816M8.46468 10.0501C8.23907 9.66233 8.06175 9.24842 7.93668 8.81759C7.78718 8.28459 7.73218 7.70759 7.87118 7.13709C8.00618 6.58209 8.33268 5.99859 9.00418 5.44559C9.12518 5.36359 9.18018 5.25359 9.19518 5.22409V5.22359C9.2212 5.16943 9.24228 5.11303 9.25818 5.05509C9.27418 4.99809 9.29168 4.92009 9.30818 4.85009L9.32568 4.77159C9.37318 4.57009 9.43468 4.35059 9.54468 4.15009C9.65168 3.95609 9.79818 3.79009 10.0162 3.68159C10.2352 3.57209 10.5742 3.49709 11.1052 3.56809C11.8317 3.66459 13.1982 3.89609 14.3712 4.49309C15.5362 5.08559 16.4172 5.98259 16.4172 7.40509C16.4172 8.06009 16.1972 8.80509 15.9322 9.39259C15.8822 9.00959 15.8102 8.69659 15.7362 8.44859C15.684 8.27152 15.6197 8.09827 15.5437 7.93009C15.5145 7.86798 15.4828 7.80709 15.4487 7.74759L15.4402 7.73409L15.4372 7.72909L15.4357 7.72659L15.4347 7.72509L15.2497 7.43809L14.9187 7.50459L14.9117 7.50609L14.8632 7.51259C14.7888 7.52094 14.714 7.52594 14.6392 7.52759C14.3309 7.5335 14.0228 7.50939 13.7192 7.45559C12.9127 7.31959 11.7277 6.94909 10.2882 5.99059L9.93818 5.75709L9.64868 6.06209C9.41018 6.31309 9.24818 6.65559 9.12968 6.98909C9.00868 7.33159 8.91718 7.71409 8.84368 8.08109C8.7827 8.3934 8.72736 8.70678 8.67768 9.02109L8.65868 9.13309C8.60518 9.45859 8.56168 9.70709 8.51768 9.86809C8.49968 9.93442 8.48201 9.99509 8.46468 10.0501Z"
                                    fill="#71717A" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.957 14.4274C8.851 14.2164 8.7205 13.9559 8.5 14.0064C5.798 14.6214 3 16.3969 3 18.2849V20.9999H21V18.2849C21 16.7979 19.264 15.3804 17.2065 14.5449L17.204 14.5399L17.197 14.5264L17.1805 14.5344C16.634 14.3144 16.065 14.1344 15.5 14.0064C15.2485 13.9489 14.9885 14.2949 14.875 14.5114H9L8.957 14.4274ZM15.7015 15.0874C15.9202 15.148 16.137 15.2159 16.352 15.2909C16.358 15.4619 16.345 15.6639 16.317 15.8699C16.2887 16.0832 16.2432 16.2939 16.181 16.4999H15.5C15.4072 16.4999 15.3162 16.5258 15.2372 16.5746C15.1583 16.6235 15.0945 16.6933 15.053 16.7764L14.553 17.7764C14.5182 17.8457 14.5001 17.9223 14.5 17.9999V18.9999C14.5 19.1325 14.5527 19.2596 14.6464 19.3534C14.7402 19.4472 14.8674 19.4999 15 19.4999H16V18.4999H15.5V18.1179L15.809 17.4999H17.191L17.5 18.1179V18.4999H17V19.4999H18C18.1326 19.4999 18.2598 19.4472 18.3536 19.3534C18.4473 19.2596 18.5 19.1325 18.5 18.9999V17.9999C18.4999 17.9223 18.4818 17.8457 18.447 17.7764L17.947 16.7764C17.9055 16.6933 17.8417 16.6235 17.7628 16.5746C17.6838 16.5258 17.5928 16.4999 17.5 16.4999H17.217C17.2781 16.2351 17.3196 15.9662 17.341 15.6954C17.8285 15.9259 18.2815 16.1904 18.674 16.4764C19.635 17.1774 20 17.8334 20 18.2849V19.9999H4V18.2849C4 17.8334 4.365 17.1774 5.326 16.4764C5.809 16.1239 6.3855 15.8049 7.0035 15.5409C7.02076 15.9393 7.0847 16.3343 7.194 16.7179L7.198 16.7319C6.89908 16.921 6.67661 17.2096 6.56987 17.5468C6.46313 17.8841 6.47899 18.2482 6.61466 18.5748C6.75033 18.9015 6.99707 19.1697 7.3113 19.3321C7.62554 19.4945 7.98705 19.5406 8.33198 19.4623C8.67692 19.384 8.98309 19.1863 9.19642 18.9042C9.40975 18.622 9.51651 18.2736 9.49785 17.9203C9.47919 17.5671 9.33631 17.2319 9.09443 16.9738C8.85256 16.7157 8.52727 16.5514 8.176 16.5099L8.154 16.4379C8.10114 16.2516 8.06187 16.0618 8.0365 15.8699C8.00847 15.676 7.99676 15.4802 8.0015 15.2844C8.00317 15.2424 8.00567 15.205 8.009 15.1724C8.069 15.1537 8.129 15.1359 8.189 15.1189L8.3965 15.5119H15.4785L15.7015 15.0874ZM8 18.5079C8.269 18.5079 8.5 18.2879 8.5 18.0004C8.5 17.7134 8.269 17.4929 8 17.4929C7.731 17.4929 7.5 17.7129 7.5 18.0004C7.5 18.2874 7.731 18.5079 8 18.5079Z"
                                    fill="#71717A" />
                            </svg>
                            <div class="sub-name">Dr.John</div>
                        </div>
                    </div>

                    <c-badge type="purple">Upcoming</c-badge>

                    <div class="secondary-details">
                        <div class="date">2025-06-08</div>
                        <c-badge type="primary">11.30 AM</c-badge>
                    </div>

                </div>
                <div class="row appoinment">
                    <div class="primary-details">
                        <div class="name">Routine Checkup</div>
                        <div class="sub-details">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.32468 4.66309L8.35218 4.54309C8.40468 4.32009 8.49068 3.99209 8.66818 3.66909C8.84918 3.34009 9.12968 3.00659 9.56968 2.78709C10.0082 2.56859 10.5577 2.48709 11.2372 2.57709C11.9872 2.67709 13.4937 2.92509 14.8247 3.60209C16.1632 4.28309 17.4172 5.45459 17.4172 7.40559C17.4172 8.41359 17.0272 9.49509 16.6492 10.2001C16.4677 10.5391 16.2522 10.8631 16.0362 11.0376C15.9867 11.0776 15.9162 11.1276 15.8287 11.1626C15.5887 11.9514 15.1109 12.6468 14.4607 13.1537C13.8104 13.6607 13.0195 13.9544 12.196 13.9948C11.3725 14.0352 10.5566 13.8202 9.85994 13.3793C9.16323 12.9384 8.61971 12.2931 8.30368 11.5316L8.29168 11.5336L8.11418 11.3181L8.11368 11.3171L8.11268 11.3161L8.11018 11.3126L8.10168 11.3026C8.05892 11.2493 8.01774 11.1948 7.97818 11.1391C7.87046 10.9892 7.76871 10.835 7.67318 10.6771C7.43818 10.2881 7.15468 9.73409 6.97318 9.08759C6.79218 8.44109 6.70918 7.68309 6.89918 6.90109C7.08418 6.14009 7.52068 5.39109 8.31118 4.72209L8.32468 4.66309ZM9.16468 10.9821C9.38153 11.6057 9.79796 12.1404 10.3495 12.5034C10.901 12.8664 11.5568 13.0374 12.2153 12.99C12.8739 12.9426 13.4984 12.6793 13.9922 12.241C14.486 11.8027 14.8214 11.2138 14.9467 10.5656L15.0032 10.5846C15.001 10.5563 15 10.528 15.0002 10.4996C15.0002 9.67209 14.8852 9.09459 14.7782 8.73459C14.7572 8.66455 14.734 8.59518 14.7087 8.52659L14.6882 8.52759H14.6682C14.2943 8.53561 13.9205 8.50714 13.5522 8.44259C12.6937 8.29759 11.5312 7.93559 10.1622 7.09709C10.1322 7.16409 10.1022 7.23959 10.0722 7.32359C9.97418 7.60109 9.89368 7.93059 9.82418 8.27859C9.76268 8.58509 9.71218 8.89359 9.66418 9.18259L9.64568 9.29659C9.59418 9.60659 9.54268 9.91109 9.48268 10.1316C9.37768 10.5146 9.27068 10.7866 9.16468 10.9816M8.46468 10.0501C8.23907 9.66233 8.06175 9.24842 7.93668 8.81759C7.78718 8.28459 7.73218 7.70759 7.87118 7.13709C8.00618 6.58209 8.33268 5.99859 9.00418 5.44559C9.12518 5.36359 9.18018 5.25359 9.19518 5.22409V5.22359C9.2212 5.16943 9.24228 5.11303 9.25818 5.05509C9.27418 4.99809 9.29168 4.92009 9.30818 4.85009L9.32568 4.77159C9.37318 4.57009 9.43468 4.35059 9.54468 4.15009C9.65168 3.95609 9.79818 3.79009 10.0162 3.68159C10.2352 3.57209 10.5742 3.49709 11.1052 3.56809C11.8317 3.66459 13.1982 3.89609 14.3712 4.49309C15.5362 5.08559 16.4172 5.98259 16.4172 7.40509C16.4172 8.06009 16.1972 8.80509 15.9322 9.39259C15.8822 9.00959 15.8102 8.69659 15.7362 8.44859C15.684 8.27152 15.6197 8.09827 15.5437 7.93009C15.5145 7.86798 15.4828 7.80709 15.4487 7.74759L15.4402 7.73409L15.4372 7.72909L15.4357 7.72659L15.4347 7.72509L15.2497 7.43809L14.9187 7.50459L14.9117 7.50609L14.8632 7.51259C14.7888 7.52094 14.714 7.52594 14.6392 7.52759C14.3309 7.5335 14.0228 7.50939 13.7192 7.45559C12.9127 7.31959 11.7277 6.94909 10.2882 5.99059L9.93818 5.75709L9.64868 6.06209C9.41018 6.31309 9.24818 6.65559 9.12968 6.98909C9.00868 7.33159 8.91718 7.71409 8.84368 8.08109C8.7827 8.3934 8.72736 8.70678 8.67768 9.02109L8.65868 9.13309C8.60518 9.45859 8.56168 9.70709 8.51768 9.86809C8.49968 9.93442 8.48201 9.99509 8.46468 10.0501Z"
                                    fill="#71717A" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.957 14.4274C8.851 14.2164 8.7205 13.9559 8.5 14.0064C5.798 14.6214 3 16.3969 3 18.2849V20.9999H21V18.2849C21 16.7979 19.264 15.3804 17.2065 14.5449L17.204 14.5399L17.197 14.5264L17.1805 14.5344C16.634 14.3144 16.065 14.1344 15.5 14.0064C15.2485 13.9489 14.9885 14.2949 14.875 14.5114H9L8.957 14.4274ZM15.7015 15.0874C15.9202 15.148 16.137 15.2159 16.352 15.2909C16.358 15.4619 16.345 15.6639 16.317 15.8699C16.2887 16.0832 16.2432 16.2939 16.181 16.4999H15.5C15.4072 16.4999 15.3162 16.5258 15.2372 16.5746C15.1583 16.6235 15.0945 16.6933 15.053 16.7764L14.553 17.7764C14.5182 17.8457 14.5001 17.9223 14.5 17.9999V18.9999C14.5 19.1325 14.5527 19.2596 14.6464 19.3534C14.7402 19.4472 14.8674 19.4999 15 19.4999H16V18.4999H15.5V18.1179L15.809 17.4999H17.191L17.5 18.1179V18.4999H17V19.4999H18C18.1326 19.4999 18.2598 19.4472 18.3536 19.3534C18.4473 19.2596 18.5 19.1325 18.5 18.9999V17.9999C18.4999 17.9223 18.4818 17.8457 18.447 17.7764L17.947 16.7764C17.9055 16.6933 17.8417 16.6235 17.7628 16.5746C17.6838 16.5258 17.5928 16.4999 17.5 16.4999H17.217C17.2781 16.2351 17.3196 15.9662 17.341 15.6954C17.8285 15.9259 18.2815 16.1904 18.674 16.4764C19.635 17.1774 20 17.8334 20 18.2849V19.9999H4V18.2849C4 17.8334 4.365 17.1774 5.326 16.4764C5.809 16.1239 6.3855 15.8049 7.0035 15.5409C7.02076 15.9393 7.0847 16.3343 7.194 16.7179L7.198 16.7319C6.89908 16.921 6.67661 17.2096 6.56987 17.5468C6.46313 17.8841 6.47899 18.2482 6.61466 18.5748C6.75033 18.9015 6.99707 19.1697 7.3113 19.3321C7.62554 19.4945 7.98705 19.5406 8.33198 19.4623C8.67692 19.384 8.98309 19.1863 9.19642 18.9042C9.40975 18.622 9.51651 18.2736 9.49785 17.9203C9.47919 17.5671 9.33631 17.2319 9.09443 16.9738C8.85256 16.7157 8.52727 16.5514 8.176 16.5099L8.154 16.4379C8.10114 16.2516 8.06187 16.0618 8.0365 15.8699C8.00847 15.676 7.99676 15.4802 8.0015 15.2844C8.00317 15.2424 8.00567 15.205 8.009 15.1724C8.069 15.1537 8.129 15.1359 8.189 15.1189L8.3965 15.5119H15.4785L15.7015 15.0874ZM8 18.5079C8.269 18.5079 8.5 18.2879 8.5 18.0004C8.5 17.7134 8.269 17.4929 8 17.4929C7.731 17.4929 7.5 17.7129 7.5 18.0004C7.5 18.2874 7.731 18.5079 8 18.5079Z"
                                    fill="#71717A" />
                            </svg>
                            <div class="sub-name">Dr.John</div>
                        </div>
                    </div>

                    <c-badge type="red">Overdue</c-badge>

                    <div class="secondary-details">
                        <div class="date">2025-06-08</div>
                        <c-badge type="primary">11.30 AM</c-badge>
                    </div>

                </div>
            </div>
        </c-card>

        <!-- BMI Chart Card -->
        <c-card class="card bmi-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">BMI Tracking</span>
                    <span class="card-subtitle">Track {{$child['name']}}'s BMI over time</span>
                </div>
                <c-button variant="secondary" size="sm">View All</c-button>

            </div>
            <hr class="divider">
            <div class="card-body">
                <canvas id="bmiChart">

                </canvas>
            </div>
        </c-card>
        <!-- Weight Chart Card -->
        <c-card class="card weight-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Weight Tracking</span>
                    <span class="card-subtitle">Track {{$child['name']}}'s Weight over time</span>
                </div>
                <c-button variant="secondary" size="sm">View All</c-button>

            </div>
            <hr class="divider">
            <div class="card-body">
                <canvas id="weightChart">

                </canvas>
            </div>
        </c-card>



    </div>

</main>

<script>
    const bmiCtx = document.getElementById('bmiChart').getContext('2d');


    const bmiGradient = bmiCtx.createLinearGradient(0, 0, 0, 300);
    bmiGradient.addColorStop(0, 'rgba(156, 39, 176, 0.3)');
    bmiGradient.addColorStop(1, 'rgba(156, 39, 176, 0)');

    new Chart(bmiCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'BMI',
                data: [70, 80, 65, 75, 90, 85, 30, 20, 95, 100, 60, 90],
                borderColor: 'rgba(156, 39, 176, 1)',
                backgroundColor: bmiGradient,
                fill: true,
                tension: 0.2,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(156, 39, 176, 1)',
                pointRadius: 4,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        stepSize: 20
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255,255,255)',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: 'rgba(0, 0, 0, 0.3)',
                    borderWidth: 1,
                    cornerRadius: 6,
                    padding: 8
                }
            }
        }
    });


    const weightCtx = document.getElementById('weightChart').getContext('2d');

    const weightGradient = weightCtx.createLinearGradient(0, 0, 0, 300);
    weightGradient.addColorStop(0, 'rgba(138, 255, 173, 0.3)');
    weightGradient.addColorStop(1, 'rgba(138, 255, 173, 0)');

    new Chart(weightCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Weight',
                data: [65, 66, 64, 67, 68, 70, 69, 71, 72, 70, 68, 69],
                borderColor: '#8AFFAD',
                backgroundColor: weightGradient,
                fill: true,
                tension: 0.2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#8AFFAD',
                pointRadius: 4,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    min:50,
                    max:90,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    cornerRadius: 6,
                    padding: 8
                }
            }
        }
    });

    const heightCtx = document.getElementById('heightChart').getContext('2d');

    const heightGradient = heightCtx.createLinearGradient(0, 0, 0, 300);
    heightGradient.addColorStop(0, 'rgba(100, 149, 237, 0.3)');
    heightGradient.addColorStop(1, 'rgba(100, 149, 237, 0)');

    new Chart(heightCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Height',
                data: [170, 171, 170, 172, 173, 174, 174, 175, 176, 175, 173, 174],
                borderColor: 'rgb(100, 149, 237)',
                backgroundColor: heightGradient,
                fill: true,
                tension: 0.2,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgb(100, 149, 237)',
                pointRadius: 4,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    min: 160,
                    max: 180,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        stepSize: 5
                    }
                }

            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    cornerRadius: 6,
                    padding: 8
                }
            }
        }
    });




</script>

@endsection