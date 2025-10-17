@extends('layout/portal')

@section('title')
Admin Dashboard
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/admin/dashboard.css') }}">
@endsection

@section('content')
<div class="top-section">

    <section class="greet">
        <h1>Good Moring, <br> <span class="user-name">{{ auth()->check() ? auth()->user()->name : 'Parent Name'}} !</span>
        </h1>
    </section>
    <section class="pill-container">
        <c-pill>
            <c-slot name="title">Total Childern</c-slot>
            <c-slot name="number">03</c-slot>
            <c-slot name="icon">
                <img src="{{asset('assets/icons/baby-01.svg')}}">
            </c-slot>
        </c-pill>
        <c-pill>
            <c-slot name="title">Active PHM</c-slot>
            <c-slot name="number">03</c-slot>
            <c-slot name="icon">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.25 9.75V8.25H18.75V6.75H17.25V8.25H15.75V9.75H17.25V11.25H18.75V9.75H20.25Z" fill="#181818"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M23.49 12.5752L24.9735 8.37075C25.3755 7.23075 24.8025 5.94075 23.6018 5.5605C22.2075 5.118 19.9275 4.5 18 4.5C16.0725 4.5 13.7925 5.118 12.3975 5.5605C11.1975 5.9415 10.6245 7.2315 11.0265 8.37075L12.5108 12.5752C12.1079 13.4886 11.9392 14.488 12.0201 15.483C12.101 16.478 12.4289 17.437 12.974 18.2733C13.5191 19.1096 14.2643 19.7966 15.142 20.2722C16.0197 20.7478 17.0021 20.9968 18.0004 20.9968C18.9987 20.9968 19.9811 20.7478 20.8588 20.2722C21.7365 19.7966 22.4817 19.1096 23.0268 18.2733C23.5719 17.437 23.8998 16.478 23.9807 15.483C24.0616 14.488 23.8929 13.4886 23.49 12.5752ZM18 6C16.32 6 14.2283 6.5535 12.8513 6.99C12.5063 7.0995 12.3038 7.4835 12.441 7.872L13.959 12.1725C16.6493 11.3175 19.3508 11.3175 22.041 12.1725L23.559 7.872C23.6963 7.4835 23.4938 7.0995 23.1488 6.99075C21.7718 6.5535 19.6793 6 18 6ZM22.3545 13.8607C22.3147 13.8527 22.2748 13.8414 22.2368 13.827C19.4063 12.7657 16.5938 12.7657 13.7633 13.827C13.725 13.8414 13.6856 13.8527 13.6455 13.8607C13.4723 14.5261 13.4539 15.2223 13.5916 15.8959C13.7294 16.5695 14.0196 17.2026 14.4401 17.7465C14.8606 18.2905 15.4001 18.7308 16.0173 19.0338C16.6345 19.3368 17.3129 19.4944 18.0004 19.4944C18.6879 19.4944 19.3663 19.3368 19.9835 19.0338C20.6007 18.7308 21.1402 18.2905 21.5607 17.7465C21.9812 17.2026 22.2714 16.5695 22.4092 15.8959C22.5469 15.2223 22.5277 14.5261 22.3545 13.8607Z" fill="#181818"/>
                    <path d="M25.5 25.5H27V27H25.5V28.5H24V27H22.5V25.5H24V24H25.5V25.5Z" fill="#181818"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.6593 21C12.6593 21 4.5 23.5447 4.5 26.6003V31.5H31.5V26.6003C31.5 23.5447 23.3407 21 23.3407 21L18.8617 24.1448C18.6093 24.3219 18.3084 24.417 18 24.417C17.6916 24.417 17.3907 24.3219 17.1383 24.1448L12.6593 21ZM23.5807 22.665L19.7242 25.3725C19.2193 25.727 18.6173 25.9172 18.0004 25.9172C17.3834 25.9172 16.7814 25.727 16.2765 25.3725L12.4193 22.665C11.3367 23.0448 10.278 23.4896 9.249 23.997C8.277 24.4815 7.3875 25.0193 6.762 25.566C6.08625 26.1563 6 26.5073 6 26.6003V30H30V26.6003C30 26.5073 29.9138 26.1563 29.238 25.5653C28.6125 25.0193 27.723 24.4815 26.7502 23.9963C25.8835 23.5694 24.9956 23.1869 24.09 22.8502C23.902 22.7802 23.7323 22.719 23.5807 22.665Z" fill="#181818"/>
                </svg>
            </c-slot>
        </c-pill>
        <c-pill>
            <c-slot name="title">Total Parents</c-slot>
            <c-slot name="number">03</c-slot>
            <c-slot name="icon">
                <img src="{{ asset('assets/icons/mother.svg') }}">
            </c-slot>
        </c-pill>
        <c-pill>
            <c-slot name="title">Access Requests</c-slot>
            <c-slot name="number">03</c-slot>
            <c-slot name="icon">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.66663 14.6667C5.77556 11.4104 10.1909 11.2571 13.3333 14.6667M11.3268 6.00001C11.3268 7.84095 9.83224 9.33334 7.98867 9.33334C6.14509 9.33334 4.65058 7.84095 4.65058 6.00001C4.65058 4.15906 6.14509 2.66667 7.98867 2.66667C9.83224 2.66667 11.3268 4.15906 11.3268 6.00001Z" stroke="#181818" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M18.6666 29.3333C21.7756 26.0771 26.1909 25.9238 29.3333 29.3333M27.3268 20.6667C27.3268 22.5076 25.8322 24 23.9887 24C22.1451 24 20.6506 22.5076 20.6506 20.6667C20.6506 18.8257 22.1451 17.3333 23.9887 17.3333C25.8322 17.3333 27.3268 18.8257 27.3268 20.6667Z" stroke="#181818" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M4 18.6667C4 23.8267 8.17333 28 13.3333 28L12 25.3333" stroke="#181818" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M20 4H28M20 8H28M20 12H24.6667" stroke="#181818" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

            </c-slot>
        </c-pill>
        <c-pill>
            <c-slot name="title">Linkage Requests</c-slot>
            <c-slot name="number">03</c-slot>
            <c-slot name="icon">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.05174 12.0494C4.10856 12.56 1.6356 13.6025 3.14179 14.907C3.87756 15.5443 4.69701 16 5.72727 16H11.6061C12.6364 16 13.4558 15.5443 14.1916 14.907C15.6978 13.6025 13.2248 12.56 12.2816 12.0494C10.0699 10.8522 7.26348 10.8522 5.05174 12.0494Z" stroke="#181818" stroke-width="1.5"/>
                    <path d="M11.6667 5.69697C11.6667 7.37056 10.3235 8.72728 8.66669 8.72728C7.00983 8.72728 5.66669 7.37056 5.66669 5.69697C5.66669 4.02338 7.00983 2.66667 8.66669 2.66667C10.3235 2.66667 11.6667 4.02338 11.6667 5.69697Z" stroke="#181818" stroke-width="1.5"/>
                    <path d="M5.33331 20C5.33331 24.4229 8.91046 28 13.3333 28L12.1905 25.7143" stroke="#181818" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M26.6667 12C26.6667 7.57714 23.0895 4 18.6667 4L19.8095 6.28571" stroke="#181818" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19.7184 25.3828C18.7752 25.8933 16.3022 26.9358 17.8084 28.2403C18.5442 28.8776 19.3636 29.3333 20.3939 29.3333H26.2727C27.303 29.3333 28.1224 28.8776 28.8582 28.2403C30.3644 26.9358 27.8914 25.8933 26.9483 25.3828C24.7365 24.1855 21.9301 24.1855 19.7184 25.3828Z" stroke="#181818" stroke-width="1.5"/>
                    <path d="M26.3333 19.0303C26.3333 20.7039 24.9902 22.0606 23.3333 22.0606C21.6765 22.0606 20.3333 20.7039 20.3333 19.0303C20.3333 17.3567 21.6765 16 23.3333 16C24.9902 16 26.3333 17.3567 26.3333 19.0303Z" stroke="#181818" stroke-width="1.5"/>
                </svg>

            </c-slot>
        </c-pill>
        <c-pill>
            <c-slot name="title">Active Doctors</c-slot>
            <c-slot name="number">03</c-slot>
            <c-slot name="icon">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.4868 6.99449L12.528 6.81449C12.6068 6.47999 12.7358 5.98799 13.002 5.50349C13.2735 5.00999 13.6943 4.50974 14.3543 4.18049C15.012 3.85274 15.8363 3.73049 16.8555 3.86549C17.9805 4.01549 20.2403 4.38749 22.2368 5.40299C24.2445 6.42449 26.1255 8.18174 26.1255 11.1082C26.1255 12.6202 25.5405 14.2425 24.9735 15.3C24.7013 15.8085 24.378 16.2945 24.054 16.5562C23.9798 16.6162 23.874 16.6912 23.7428 16.7437C23.3827 17.9269 22.6661 18.97 21.6907 19.7304C20.7154 20.4908 19.529 20.9315 18.2938 20.992C17.0585 21.0526 15.8347 20.7302 14.7897 20.0688C13.7446 19.4075 12.9293 18.4395 12.4553 17.2972L12.4373 17.3002L12.171 16.977L12.1703 16.9755L12.1688 16.974L12.165 16.9687L12.1523 16.9537C12.0881 16.8738 12.0264 16.792 11.967 16.7085C11.8054 16.4836 11.6528 16.2524 11.5095 16.0155C11.157 15.432 10.7318 14.601 10.4595 13.6312C10.188 12.6615 10.0635 11.5245 10.3485 10.3515C10.626 9.20999 11.2808 8.08649 12.4665 7.08299L12.4868 6.99449ZM13.7468 16.473C14.0721 17.4084 14.6967 18.2105 15.5239 18.755C16.3512 19.2995 17.3349 19.556 18.3227 19.4849C19.3105 19.4137 20.2473 19.0188 20.988 18.3614C21.7287 17.704 22.2319 16.8206 22.4198 15.8482L22.5045 15.8767C22.5012 15.8343 22.4997 15.7918 22.5 15.7492C22.5 14.508 22.3275 13.6417 22.167 13.1017C22.1355 12.9967 22.1008 12.8926 22.0628 12.7897L22.032 12.7912H22.002C21.4412 12.8033 20.8805 12.7606 20.328 12.6637C19.0403 12.4462 17.2965 11.9032 15.243 10.6455C15.198 10.746 15.153 10.8592 15.108 10.9852C14.961 11.4015 14.8403 11.8957 14.736 12.4177C14.6438 12.8775 14.568 13.3402 14.496 13.7737L14.4683 13.9447C14.391 14.4097 14.3138 14.8665 14.2238 15.1972C14.0663 15.7717 13.9058 16.1797 13.7468 16.4722M12.6968 15.075C12.3584 14.4934 12.0924 13.8725 11.9048 13.2262C11.6805 12.4267 11.598 11.5612 11.8065 10.7055C12.009 9.87299 12.4988 8.99774 13.506 8.16824C13.6875 8.04524 13.77 7.88024 13.7925 7.83599V7.83524C13.8315 7.754 13.8632 7.66941 13.887 7.58249C13.911 7.49699 13.9373 7.37999 13.962 7.27499L13.9883 7.15724C14.0595 6.85499 14.1518 6.52574 14.3168 6.22499C14.4773 5.93399 14.697 5.68499 15.024 5.52224C15.3525 5.35799 15.861 5.24549 16.6575 5.35199C17.7473 5.49674 19.797 5.84399 21.5565 6.73949C23.304 7.62824 24.6255 8.97374 24.6255 11.1075C24.6255 12.09 24.2955 13.2075 23.898 14.0887C23.823 13.5142 23.715 13.0447 23.604 12.6727C23.5258 12.4071 23.4293 12.1473 23.3153 11.895C23.2715 11.8018 23.224 11.7105 23.1728 11.6212L23.16 11.601L23.1555 11.5935L23.1533 11.5897L23.1518 11.5875L22.8743 11.157L22.3778 11.2567L22.3673 11.259L22.2945 11.2687C22.1829 11.2813 22.0708 11.2888 21.9585 11.2912C21.4961 11.3001 21.0339 11.2639 20.5785 11.1832C19.3688 10.9792 17.5913 10.4235 15.432 8.98574L14.907 8.63549L14.4728 9.09299C14.115 9.46949 13.872 9.98324 13.6943 10.4835C13.5128 10.9972 13.3755 11.571 13.2653 12.1215C13.1738 12.59 13.0908 13.06 13.0163 13.5315L12.9878 13.6995C12.9075 14.1877 12.8423 14.5605 12.7763 14.802C12.7493 14.9015 12.7228 14.9925 12.6968 15.075Z" fill="#181818"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.4355 21.6413C13.2765 21.3248 13.0808 20.934 12.75 21.0098C8.697 21.9323 4.5 24.5955 4.5 27.4275V31.5H31.5V27.4275C31.5 25.197 28.896 23.0708 25.8097 21.8175L25.806 21.81L25.7955 21.7898L25.7708 21.8018C24.951 21.4718 24.0975 21.2018 23.25 21.0098C22.8727 20.9235 22.4827 21.4425 22.3125 21.7673H13.5L13.4355 21.6413ZM23.5522 22.6312C23.8802 22.7222 24.2055 22.824 24.528 22.9365C24.537 23.193 24.5175 23.496 24.4755 23.805C24.433 24.125 24.3648 24.441 24.2715 24.75H23.25C23.1108 24.7501 22.9743 24.7889 22.8559 24.8622C22.7374 24.9354 22.6417 25.0402 22.5795 25.1648L21.8295 26.6648C21.7773 26.7688 21.7501 26.8836 21.75 27V28.5C21.75 28.6989 21.829 28.8897 21.9697 29.0303C22.1103 29.171 22.3011 29.25 22.5 29.25H24V27.75H23.25V27.177L23.7135 26.25H25.7865L26.25 27.177V27.75H25.5V29.25H27C27.1989 29.25 27.3897 29.171 27.5303 29.0303C27.671 28.8897 27.75 28.6989 27.75 28.5V27C27.7499 26.8836 27.7227 26.7688 27.6705 26.6648L26.9205 25.1648C26.8583 25.0402 26.7626 24.9354 26.6441 24.8622C26.5257 24.7889 26.3892 24.7501 26.25 24.75H25.8255C25.9172 24.3529 25.9794 23.9495 26.0115 23.5433C26.7428 23.889 27.4222 24.2857 28.011 24.7147C29.4525 25.7662 30 26.7502 30 27.4275V30H6V27.4275C6 26.7502 6.5475 25.7662 7.989 24.7147C8.7135 24.186 9.57825 23.7075 10.5052 23.3115C10.5311 23.9091 10.627 24.5017 10.791 25.077L10.797 25.098C10.3486 25.3817 10.0149 25.8146 9.8548 26.3205C9.69469 26.8263 9.71849 27.3724 9.92199 27.8624C10.1255 28.3524 10.4956 28.7547 10.967 28.9983C11.4383 29.2419 11.9806 29.3111 12.498 29.1936C13.0154 29.0762 13.4746 28.7797 13.7946 28.3565C14.1146 27.9332 14.2748 27.4106 14.2468 26.8807C14.2188 26.3509 14.0045 25.848 13.6417 25.4609C13.2788 25.0737 12.7909 24.8273 12.264 24.765L12.231 24.657C12.1517 24.3776 12.0928 24.0929 12.0547 23.805C12.0127 23.5142 11.9951 23.2205 12.0023 22.9268C12.0048 22.8638 12.0085 22.8077 12.0135 22.7587C12.1035 22.7307 12.1935 22.704 12.2835 22.6785L12.5947 23.268H23.2178L23.5522 22.6312ZM12 27.762C12.4035 27.762 12.75 27.432 12.75 27.0007C12.75 26.5702 12.4035 26.2395 12 26.2395C11.5965 26.2395 11.25 26.5695 11.25 27.0007C11.25 27.4312 11.5965 27.762 12 27.762Z" fill="#181818"/>
                </svg>
            </c-slot>
        </c-pill>
    </section>
</div>

<main class="container">
    <div class="left-col">
        <!-- Monthly Vaccinations Completed Card -->
        <c-card class="card vaccine-chart">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Monthly Vaccinations Completed</span>
                    <span class="card-subtitle">Tracing vaccinations completions over time</span>
                </div>
                <!-- Child Selector -->
            </div>
            <hr class="divider">
            <div class="card-body vaccine-card">
                <canvas id="lineAreaChart"></canvas>
            </div>
        </c-card>

        <!-- Parent approval requests -->
        <c-card class="card event-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Parent Approval Request</span>
                    <span class="card-subtitle">Latest parent accounts to be verified</span>
                </div>
                <c-button varient="secondary" size="sm">View All</c-button>
            </div>
            <hr class="divider">

            <div class="card-body">
                <!-- Event Item -->
                <div class="row event">
                    <div class="primary-details">
                        <div class="name">User 1</div>

                        <div class="sub-details">
                            <div class="sub-name">
                                Parent
                            </div>
                        </div>
                        
                    </div>
                    <div class="secondary-details">
                        <c-badge type="secondary">Pending</c-badge>
                    </div>

                    <div class="secondary-details">
                        <div class="approval-request-btn-group">
                            <c-button variant="primary" size="sm">
                                <img src="{{ asset('assets/icons/checkmark-circle-02.svg')}}">
                                Approve
                            </c-button>
                            <c-button variant="destructive" size="sm">
                                <img class="deny-icon" src="{{ asset('assets/icons/cancel-circle.svg')}}">
                                Deny
                            </c-button>
                        </div>
                    </div>
                </div>
                <div class="row event">
                    <div class="primary-details">
                        <div class="name">User 1</div>

                        <div class="sub-details">
                            <div class="sub-name">
                                Parent
                            </div>
                        </div>
                        
                    </div>
                    <div class="secondary-details">
                        <c-badge type="secondary">Pending</c-badge>
                    </div>

                    <div class="secondary-details">
                        <div class="approval-request-btn-group">
                            <c-button variant="primary" size="sm">
                                <img src="{{ asset('assets/icons/checkmark-circle-02.svg')}}">
                                Approve
                            </c-button>
                            <c-button variant="destructive" size="sm">
                                <img class="deny-icon" src="{{ asset('assets/icons/cancel-circle.svg')}}">
                                Deny
                            </c-button>
                        </div>
                    </div>
                </div>
                <div class="row event">
                    <div class="primary-details">
                        <div class="name">User 1</div>

                        <div class="sub-details">
                            <div class="sub-name">
                                Parent
                            </div>
                        </div>
                        
                    </div>
                    <div class="secondary-details">
                        <c-badge type="secondary">Pending</c-badge>
                    </div>

                    <div class="secondary-details">
                        <div class="approval-request-btn-group">
                            <c-button variant="primary" size="sm">
                                <img src="{{ asset('assets/icons/checkmark-circle-02.svg')}}">
                                Approve
                            </c-button>
                            <c-button variant="destructive" size="sm">
                                <img class="deny-icon" src="{{ asset('assets/icons/cancel-circle.svg')}}">
                                Deny
                            </c-button>
                        </div>
                    </div>
                </div>
                <div class="row event">
                    <div class="primary-details">
                        <div class="name">User 1</div>

                        <div class="sub-details">
                            <div class="sub-name">
                                Parent
                            </div>
                        </div>
                        
                    </div>
                    <div class="secondary-details">
                        <c-badge type="secondary">Pending</c-badge>

                        
                    </div>

                    <div class="secondary-details">
                        <div class="approval-request-btn-group">
                            <c-button variant="primary" size="sm">
                                <img src="{{ asset('assets/icons/checkmark-circle-02.svg')}}">
                                Approve
                            </c-button>
                            <c-button variant="destructive" size="sm">
                                <img class="deny-icon" src="{{ asset('assets/icons/cancel-circle.svg')}}">
                                Deny
                            </c-button>
                        </div>
                    </div>
                </div>
            </div>
        </c-card>

         <!-- Upcoming Events Card -->
        <c-card class="card event-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Upcoming Events & Campaigns</span>
                    <span class="card-subtitle">Scheduled health events and vaccination drives</span>
                </div>
                <c-button varient="secondary" size="sm">View All</c-button>
            </div>
            <hr class="divider">

            <div class="card-body">
                <!-- Event Item -->
                <div class="row event">
                    <div class="primary-details">
                        <div class="name">Maternal Health Seminar</div>
                        <div class="sub-details">
                            <!-- Calender Icon-->
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 2V4M6 2V4" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M11.9955 13H12.0045M11.9955 17H12.0045M15.991 13H16M8 13H8.00897M8 17H8.00897"
                                    stroke="#18181B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.5 8H20.5" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M2.5 12.2432C2.5 7.88594 2.5 5.70728 3.75212 4.35364C5.00424 3 7.01949 3 11.05 3H12.95C16.9805 3 18.9958 3 20.2479 4.35364C21.5 5.70728 21.5 7.88594 21.5 12.2432V12.7568C21.5 17.1141 21.5 19.2927 20.2479 20.6464C18.9958 22 16.9805 22 12.95 22H11.05C7.01949 22 5.00424 22 3.75212 20.6464C2.5 19.2927 2.5 17.1141 2.5 12.7568V12.2432Z"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M3 8H21" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                            <div class="sub-name">2024-06-15 at 09.00 AM</div>
                        </div>
                        <div class="sub-details">
                            <!-- Participants Icon-->
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.774 18C21.5233 18 22.1193 17.5285 22.6545 16.8691C23.7499 15.5194 21.9513 14.4408 21.2654 13.9126C20.568 13.3756 19.7894 13.0714 19 13M18 11C19.3807 11 20.5 9.88071 20.5 8.5C20.5 7.11929 19.3807 6 18 6"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M3.22596 18C2.47666 18 1.88067 17.5285 1.34555 16.8691C0.250089 15.5194 2.04867 14.4408 2.73465 13.9126C3.43197 13.3756 4.21058 13.0714 5 13M5.5 11C4.11929 11 3 9.88071 3 8.5C3 7.11929 4.11929 6 5.5 6"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M8.0838 15.1112C7.06203 15.743 4.38299 17.0331 6.0147 18.6474C6.81178 19.436 7.69952 20 8.81563 20H15.1844C16.3005 20 17.1882 19.436 17.9853 18.6474C19.617 17.0331 16.938 15.743 15.9162 15.1112C13.5201 13.6296 10.4799 13.6296 8.0838 15.1112Z"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M15.5 7.5C15.5 9.433 13.933 11 12 11C10.067 11 8.5 9.433 8.5 7.5C8.5 5.567 10.067 4 12 4C13.933 4 15.5 5.567 15.5 7.5Z"
                                    stroke="#71717A" stroke-width="1.5" />
                            </svg>

                            <div class="sub-name">45 Participants</div>
                            <!-- Location Icon -->
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.5 9C14.5 10.3807 13.3807 11.5 12 11.5C10.6193 11.5 9.5 10.3807 9.5 9C9.5 7.61929 10.6193 6.5 12 6.5C13.3807 6.5 14.5 7.61929 14.5 9Z"
                                    stroke="#71717A" stroke-width="1.5" />
                                <path
                                    d="M13.2574 17.4936C12.9201 17.8184 12.4693 18 12.0002 18C11.531 18 11.0802 17.8184 10.7429 17.4936C7.6543 14.5008 3.51519 11.1575 5.53371 6.30373C6.6251 3.67932 9.24494 2 12.0002 2C14.7554 2 17.3752 3.67933 18.4666 6.30373C20.4826 11.1514 16.3536 14.5111 13.2574 17.4936Z"
                                    stroke="#71717A" stroke-width="1.5" />
                                <path d="M18 20C18 21.1046 15.3137 22 12 22C8.68629 22 6 21.1046 6 20" stroke="#71717A"
                                    stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <div class="sub-name">RHU Center A</div>
                        </div>
                    </div>
                    <div class="secondary-details">
                        <c-badge type="purple">Upcoming</c-badge>
                    </div>


                </div>
                <!-- Repeatable rows for other events -->
                <div class="row event">
                    <div class="primary-details">
                        <div class="name">Baby Care Seminar</div>
                        <div class="sub-details">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 2V4M6 2V4" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M11.9955 13H12.0045M11.9955 17H12.0045M15.991 13H16M8 13H8.00897M8 17H8.00897"
                                    stroke="#18181B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.5 8H20.5" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M2.5 12.2432C2.5 7.88594 2.5 5.70728 3.75212 4.35364C5.00424 3 7.01949 3 11.05 3H12.95C16.9805 3 18.9958 3 20.2479 4.35364C21.5 5.70728 21.5 7.88594 21.5 12.2432V12.7568C21.5 17.1141 21.5 19.2927 20.2479 20.6464C18.9958 22 16.9805 22 12.95 22H11.05C7.01949 22 5.00424 22 3.75212 20.6464C2.5 19.2927 2.5 17.1141 2.5 12.7568V12.2432Z"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M3 8H21" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                            <div class="sub-name">2024-06-15 at 03.00 PM</div>
                        </div>
                        <div class="sub-details">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.774 18C21.5233 18 22.1193 17.5285 22.6545 16.8691C23.7499 15.5194 21.9513 14.4408 21.2654 13.9126C20.568 13.3756 19.7894 13.0714 19 13M18 11C19.3807 11 20.5 9.88071 20.5 8.5C20.5 7.11929 19.3807 6 18 6"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M3.22596 18C2.47666 18 1.88067 17.5285 1.34555 16.8691C0.250089 15.5194 2.04867 14.4408 2.73465 13.9126C3.43197 13.3756 4.21058 13.0714 5 13M5.5 11C4.11929 11 3 9.88071 3 8.5C3 7.11929 4.11929 6 5.5 6"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M8.0838 15.1112C7.06203 15.743 4.38299 17.0331 6.0147 18.6474C6.81178 19.436 7.69952 20 8.81563 20H15.1844C16.3005 20 17.1882 19.436 17.9853 18.6474C19.617 17.0331 16.938 15.743 15.9162 15.1112C13.5201 13.6296 10.4799 13.6296 8.0838 15.1112Z"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M15.5 7.5C15.5 9.433 13.933 11 12 11C10.067 11 8.5 9.433 8.5 7.5C8.5 5.567 10.067 4 12 4C13.933 4 15.5 5.567 15.5 7.5Z"
                                    stroke="#71717A" stroke-width="1.5" />
                            </svg>

                            <div class="sub-name">45 Participants</div>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.5 9C14.5 10.3807 13.3807 11.5 12 11.5C10.6193 11.5 9.5 10.3807 9.5 9C9.5 7.61929 10.6193 6.5 12 6.5C13.3807 6.5 14.5 7.61929 14.5 9Z"
                                    stroke="#71717A" stroke-width="1.5" />
                                <path
                                    d="M13.2574 17.4936C12.9201 17.8184 12.4693 18 12.0002 18C11.531 18 11.0802 17.8184 10.7429 17.4936C7.6543 14.5008 3.51519 11.1575 5.53371 6.30373C6.6251 3.67932 9.24494 2 12.0002 2C14.7554 2 17.3752 3.67933 18.4666 6.30373C20.4826 11.1514 16.3536 14.5111 13.2574 17.4936Z"
                                    stroke="#71717A" stroke-width="1.5" />
                                <path d="M18 20C18 21.1046 15.3137 22 12 22C8.68629 22 6 21.1046 6 20" stroke="#71717A"
                                    stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <div class="sub-name">RHU Center A</div>
                        </div>
                    </div>
                    <div class="secondary-details">
                        <c-badge type="purple">Upcoming</c-badge>
                    </div>

                </div>
                <div class="row event">
                    <div class="primary-details">
                        <div class="name">Maternal Health Seminar</div>
                        <div class="sub-details">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 2V4M6 2V4" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M11.9955 13H12.0045M11.9955 17H12.0045M15.991 13H16M8 13H8.00897M8 17H8.00897"
                                    stroke="#18181B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.5 8H20.5" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M2.5 12.2432C2.5 7.88594 2.5 5.70728 3.75212 4.35364C5.00424 3 7.01949 3 11.05 3H12.95C16.9805 3 18.9958 3 20.2479 4.35364C21.5 5.70728 21.5 7.88594 21.5 12.2432V12.7568C21.5 17.1141 21.5 19.2927 20.2479 20.6464C18.9958 22 16.9805 22 12.95 22H11.05C7.01949 22 5.00424 22 3.75212 20.6464C2.5 19.2927 2.5 17.1141 2.5 12.7568V12.2432Z"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M3 8H21" stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                            <div class="sub-name">2024-01-15 at 09.00 AM</div>
                        </div>
                        <div class="sub-details">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.774 18C21.5233 18 22.1193 17.5285 22.6545 16.8691C23.7499 15.5194 21.9513 14.4408 21.2654 13.9126C20.568 13.3756 19.7894 13.0714 19 13M18 11C19.3807 11 20.5 9.88071 20.5 8.5C20.5 7.11929 19.3807 6 18 6"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M3.22596 18C2.47666 18 1.88067 17.5285 1.34555 16.8691C0.250089 15.5194 2.04867 14.4408 2.73465 13.9126C3.43197 13.3756 4.21058 13.0714 5 13M5.5 11C4.11929 11 3 9.88071 3 8.5C3 7.11929 4.11929 6 5.5 6"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M8.0838 15.1112C7.06203 15.743 4.38299 17.0331 6.0147 18.6474C6.81178 19.436 7.69952 20 8.81563 20H15.1844C16.3005 20 17.1882 19.436 17.9853 18.6474C19.617 17.0331 16.938 15.743 15.9162 15.1112C13.5201 13.6296 10.4799 13.6296 8.0838 15.1112Z"
                                    stroke="#71717A" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M15.5 7.5C15.5 9.433 13.933 11 12 11C10.067 11 8.5 9.433 8.5 7.5C8.5 5.567 10.067 4 12 4C13.933 4 15.5 5.567 15.5 7.5Z"
                                    stroke="#71717A" stroke-width="1.5" />
                            </svg>

                            <div class="sub-name">45 Participants</div>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.5 9C14.5 10.3807 13.3807 11.5 12 11.5C10.6193 11.5 9.5 10.3807 9.5 9C9.5 7.61929 10.6193 6.5 12 6.5C13.3807 6.5 14.5 7.61929 14.5 9Z"
                                    stroke="#71717A" stroke-width="1.5" />
                                <path
                                    d="M13.2574 17.4936C12.9201 17.8184 12.4693 18 12.0002 18C11.531 18 11.0802 17.8184 10.7429 17.4936C7.6543 14.5008 3.51519 11.1575 5.53371 6.30373C6.6251 3.67932 9.24494 2 12.0002 2C14.7554 2 17.3752 3.67933 18.4666 6.30373C20.4826 11.1514 16.3536 14.5111 13.2574 17.4936Z"
                                    stroke="#71717A" stroke-width="1.5" />
                                <path d="M18 20C18 21.1046 15.3137 22 12 22C8.68629 22 6 21.1046 6 20" stroke="#71717A"
                                    stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <div class="sub-name">RHU Center A</div>
                        </div>
                    </div>
                    <div class="secondary-details">
                        <c-badge type="green">Completed</c-badge>
                    </div>

                </div>
            </div>
        </c-card>

    </div>

    <div class="right-col">
        <!-- Weekly Appointment Chart Card -->
        <c-card class="card growth-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Weekly Appointment Trends</span>
                    <span class="card-subtitle">Appointment completion rates by day</span>
                </div>
                <!-- Child Selector -->
            </div>
            <hr class="divider">
            <div class="card-body growth-card">
                <canvas id="barChart"></canvas>
            </div>
        </c-card>

        <!-- Recent activity -->
         <c-card class="card activity-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Recent Activity</span>
                    <span class="card-subtitle">Recent activity of all users</span>
                </div>
                <c-button varient="secondary" size="sm">Check Logs</c-button>
            </div>
            <hr class="divider">
            <div class="card-body">
                <!-- Single activity row  -->
                <div class="row activity">
                    <div class="primary-details">
                        <div class="name">User did something</div>
                        <div class="sub-details">
                            <!-- Doctor Icon -->
                            <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="7.00002" cy="7.50002" r="5.83333" stroke="#71717A"/>
                                <path d="M7 5.16669V7.50002L8.16667 8.66669" stroke="#71717A" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <div class="sub-name">13:46:23, Sun 2025</div>
                        </div>
                    </div>
                    <div class="secondary-details">
                        <c-badge type="green">Doctor</c-badge>
                    </div>
                </div>
                <div class="row activity">
                    <div class="primary-details">
                        <div class="name">User did something</div>
                        <div class="sub-details">
                            <!-- Doctor Icon -->
                            <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="7.00002" cy="7.50002" r="5.83333" stroke="#71717A"/>
                                <path d="M7 5.16669V7.50002L8.16667 8.66669" stroke="#71717A" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <div class="sub-name">13:46:23, Sun 2025</div>
                        </div>
                    </div>
                    <div class="secondary-details">
                        <c-badge type="blue">PHM</c-badge>
                    </div>
                </div>
                <div class="row activity">
                    <div class="primary-details">
                        <div class="name">User did something</div>
                        <div class="sub-details">
                            <!-- Doctor Icon -->
                            <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="7.00002" cy="7.50002" r="5.83333" stroke="#71717A"/>
                                <path d="M7 5.16669V7.50002L8.16667 8.66669" stroke="#71717A" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <div class="sub-name">13:46:23, Sun 2025</div>
                        </div>
                    </div>
                    <div class="secondary-details">
                        <c-badge type="purple">Parent</c-badge>
                    </div>
                </div>
            </div>
        </c-card>

        <!-- Today's Appoinments Card -->
        <c-card class="card appoinment-card">
            <div class="header">
                <div class="title-section">
                    <span class="card-title">Today's Appoinments</span>
                    <span class="card-subtitle">Scheduled appointments for today</span>
                </div>
                <c-button varient="secondary" size="sm">View Schedule</c-button>
            </div>
            <hr class="divider">
            <div class="card-body">
                <!-- Single appointment row  -->
                <div class="row appoinment">
                    <div class="primary-details">
                        <div class="name">Baby Sara - <span>Routine Checkup</span></div>
                        <div class="sub-details">
                            <!-- Doctor Icon -->
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.32468 4.66309L8.35218 4.54309C8.40468 4.32009 8.49068 3.99209 8.66818 3.66909C8.84918 3.34009 9.12968 3.00659 9.56968 2.78709C10.0082 2.56859 10.5577 2.48709 11.2372 2.57709C11.9872 2.67709 13.4937 2.92509 14.8247 3.60209C16.1632 4.28309 17.4172 5.45459 17.4172 7.40559C17.4172 8.41359 17.0272 9.49509 16.6492 10.2001C16.4677 10.5391 16.2522 10.8631 16.0362 11.0376C15.9867 11.0776 15.9162 11.1276 15.8287 11.1626C15.5887 11.9514 15.1109 12.6468 14.4607 13.1537C13.8104 13.6607 13.0195 13.9544 12.196 13.9948C11.3725 14.0352 10.5566 13.8202 9.85994 13.3793C9.16323 12.9384 8.61971 12.2931 8.30368 11.5316L8.29168 11.5336L8.11418 11.3181L8.11368 11.3171L8.11268 11.3161L8.11018 11.3126L8.10168 11.3026C8.05892 11.2493 8.01774 11.1948 7.97818 11.1391C7.87046 10.9892 7.76871 10.835 7.67318 10.6771C7.43818 10.2881 7.15468 9.73409 6.97318 9.08759C6.79218 8.44109 6.70918 7.68309 6.89918 6.90109C7.08418 6.14009 7.52068 5.39109 8.31118 4.72209L8.32468 4.66309ZM9.16468 10.9821C9.38153 11.6057 9.79796 12.1404 10.3495 12.5034C10.901 12.8664 11.5568 13.0374 12.2153 12.99C12.8739 12.9426 13.4984 12.6793 13.9922 12.241C14.486 11.8027 14.8214 11.2138 14.9467 10.5656L15.0032 10.5846C15.001 10.5563 15 10.528 15.0002 10.4996C15.0002 9.67209 14.8852 9.09459 14.7782 8.73459C14.7572 8.66455 14.734 8.59518 14.7087 8.52659L14.6882 8.52759H14.6682C14.2943 8.53561 13.9205 8.50714 13.5522 8.44259C12.6937 8.29759 11.5312 7.93559 10.1622 7.09709C10.1322 7.16409 10.1022 7.23959 10.0722 7.32359C9.97418 7.60109 9.89368 7.93059 9.82418 8.27859C9.76268 8.58509 9.71218 8.89359 9.66418 9.18259L9.64568 9.29659C9.59418 9.60659 9.54268 9.91109 9.48268 10.1316C9.37768 10.5146 9.27068 10.7866 9.16468 10.9816M8.46468 10.0501C8.23907 9.66233 8.06175 9.24842 7.93668 8.81759C7.78718 8.28459 7.73218 7.70759 7.87118 7.13709C8.00618 6.58209 8.33268 5.99859 9.00418 5.44559C9.12518 5.36359 9.18018 5.25359 9.19518 5.22409V5.22359C9.2212 5.16943 9.24228 5.11303 9.25818 5.05509C9.27418 4.99809 9.29168 4.92009 9.30818 4.85009L9.32568 4.77159C9.37318 4.57009 9.43468 4.35059 9.54468 4.15009C9.65168 3.95609 9.79818 3.79009 10.0162 3.68159C10.2352 3.57209 10.5742 3.49709 11.1052 3.56809C11.8317 3.66459 13.1982 3.89609 14.3712 4.49309C15.5362 5.08559 16.4172 5.98259 16.4172 7.40509C16.4172 8.06009 16.1972 8.80509 15.9322 9.39259C15.8822 9.00959 15.8102 8.69659 15.7362 8.44859C15.684 8.27152 15.6197 8.09827 15.5437 7.93009C15.5145 7.86798 15.4828 7.80709 15.4487 7.74759L15.4402 7.73409L15.4372 7.72909L15.4357 7.72659L15.4347 7.72509L15.2497 7.43809L14.9187 7.50459L14.9117 7.50609L14.8632 7.51259C14.7888 7.52094 14.714 7.52594 14.6392 7.52759C14.3309 7.5335 14.0228 7.50939 13.7192 7.45559C12.9127 7.31959 11.7277 6.94909 10.2882 5.99059L9.93818 5.75709L9.64868 6.06209C9.41018 6.31309 9.24818 6.65559 9.12968 6.98909C9.00868 7.33159 8.91718 7.71409 8.84368 8.08109C8.7827 8.3934 8.72736 8.70678 8.67768 9.02109L8.65868 9.13309C8.60518 9.45859 8.56168 9.70709 8.51768 9.86809C8.49968 9.93442 8.48201 9.99509 8.46468 10.0501Z"
                                    fill="#71717A" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.957 14.4274C8.851 14.2164 8.7205 13.9559 8.5 14.0064C5.798 14.6214 3 16.3969 3 18.2849V20.9999H21V18.2849C21 16.7979 19.264 15.3804 17.2065 14.5449L17.204 14.5399L17.197 14.5264L17.1805 14.5344C16.634 14.3144 16.065 14.1344 15.5 14.0064C15.2485 13.9489 14.9885 14.2949 14.875 14.5114H9L8.957 14.4274ZM15.7015 15.0874C15.9202 15.148 16.137 15.2159 16.352 15.2909C16.358 15.4619 16.345 15.6639 16.317 15.8699C16.2887 16.0832 16.2432 16.2939 16.181 16.4999H15.5C15.4072 16.4999 15.3162 16.5258 15.2372 16.5746C15.1583 16.6235 15.0945 16.6933 15.053 16.7764L14.553 17.7764C14.5182 17.8457 14.5001 17.9223 14.5 17.9999V18.9999C14.5 19.1325 14.5527 19.2596 14.6464 19.3534C14.7402 19.4472 14.8674 19.4999 15 19.4999H16V18.4999H15.5V18.1179L15.809 17.4999H17.191L17.5 18.1179V18.4999H17V19.4999H18C18.1326 19.4999 18.2598 19.4472 18.3536 19.3534C18.4473 19.2596 18.5 19.1325 18.5 18.9999V17.9999C18.4999 17.9223 18.4818 17.8457 18.447 17.7764L17.947 16.7764C17.9055 16.6933 17.8417 16.6235 17.7628 16.5746C17.6838 16.5258 17.5928 16.4999 17.5 16.4999H17.217C17.2781 16.2351 17.3196 15.9662 17.341 15.6954C17.8285 15.9259 18.2815 16.1904 18.674 16.4764C19.635 17.1774 20 17.8334 20 18.2849V19.9999H4V18.2849C4 17.8334 4.365 17.1774 5.326 16.4764C5.809 16.1239 6.3855 15.8049 7.0035 15.5409C7.02076 15.9393 7.0847 16.3343 7.194 16.7179L7.198 16.7319C6.89908 16.921 6.67661 17.2096 6.56987 17.5468C6.46313 17.8841 6.47899 18.2482 6.61466 18.5748C6.75033 18.9015 6.99707 19.1697 7.3113 19.3321C7.62554 19.4945 7.98705 19.5406 8.33198 19.4623C8.67692 19.384 8.98309 19.1863 9.19642 18.9042C9.40975 18.622 9.51651 18.2736 9.49785 17.9203C9.47919 17.5671 9.33631 17.2319 9.09443 16.9738C8.85256 16.7157 8.52727 16.5514 8.176 16.5099L8.154 16.4379C8.10114 16.2516 8.06187 16.0618 8.0365 15.8699C8.00847 15.676 7.99676 15.4802 8.0015 15.2844C8.00317 15.2424 8.00567 15.205 8.009 15.1724C8.069 15.1537 8.129 15.1359 8.189 15.1189L8.3965 15.5119H15.4785L15.7015 15.0874ZM8 18.5079C8.269 18.5079 8.5 18.2879 8.5 18.0004C8.5 17.7134 8.269 17.4929 8 17.4929C7.731 17.4929 7.5 17.7129 7.5 18.0004C7.5 18.2874 7.731 18.5079 8 18.5079Z"
                                    fill="#71717A" />
                            </svg>
                            <div class="sub-name">Dr.Smith</div>
                        </div>
                    </div>
                    <div class="secondary-details">
                        <div class="date">12:00 PM</div>
                        <c-badge type="secondary">Cancelled</c-badge>
                    </div>
                </div>
                <!-- Repeatable rows for other appoinments -->
                <div class="row appoinment">
                    <div class="primary-details">
                        <div class="name">Baby Sara - <span>Routine Checkup</span></div>
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
                    <div class="secondary-details">
                        <div class="date">9:30 AM</div>
                        <c-badge type="secondary">Finished</c-badge>
                    </div>

                </div>
                <div class="row appoinment">
                    <div class="primary-details">
                        <div class="name">Baby Mike - <span>Routine Checkup</span></div>
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
                            <div class="sub-name">Dr.Smith</div>
                        </div>
                    </div>
                    <div class="secondary-details">
                        <div class="date">10:00 AM</div>
                        <c-badge type="secondary">Scheduled</c-badge>
                    </div>

                </div>
            </div>
        </c-card>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- Data for the left chart (monthly) ---
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const scheduled = [2, 0, 0, 100, 80, 60, 80, 20, 10, 40, 60, 50]; // blue-ish small area
    const completed = [150,110,220,240,420,400,310,330,320,350,280,180]; // pink/peach large area

    // Create gradients (requires canvas context)
    const ctxLine = document.getElementById('lineAreaChart').getContext('2d');
    const gradCompleted = ctxLine.createLinearGradient(0,0,0,220);
    gradCompleted.addColorStop(0, 'rgba(255,120,120,0.26)');
    gradCompleted.addColorStop(1, 'rgba(255,120,120,0.04)');

    const gradScheduled = ctxLine.createLinearGradient(0,0,0,220);
    gradScheduled.addColorStop(0, 'rgba(88,116,255,0.18)');
    gradScheduled.addColorStop(1, 'rgba(88,116,255,0.02)');

    // Left: area line chart
    const lineAreaChart = new Chart(ctxLine, {
        type: 'line',
        data: {
        labels: months,
        datasets: [
            {
            label: 'Scheduled',
            data: scheduled,
            tension: 0.36,
            borderWidth: 2,
            borderColor: 'rgba(88,116,255,1)',
            backgroundColor: gradScheduled,
            pointRadius: 3,
            pointBackgroundColor: 'rgba(88,116,255,1)',
            fill: true,
            yAxisID: 'y',
            },
            {
            label: 'Completed',
            data: completed,
            tension: 0.36,
            borderWidth: 2,
            borderColor: 'rgba(255,100,100,1)',
            backgroundColor: gradCompleted,
            pointRadius: 0,
            fill: true,
            yAxisID: 'y',
            }
        ]
        },
        options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: {
            position: 'bottom',
            labels: { boxWidth: 10, padding: 12, usePointStyle: true }
            },
            tooltip: {
            backgroundColor: '#fff',
            titleColor: '#111',
            bodyColor: '#111',
            borderColor: '#eee',
            borderWidth: 1,
            }
        },
        scales: {
            x: {
            grid: { display: false },
            ticks: { color: '#6b7280' }
            },
            y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(150,160,180,0.08)',
                borderDash: [4,4],
            },
            ticks: { color: '#6b7280' }
            }
        }
        }
    });

    // --- Data for the right chart (weekly) ---
    const days = ['Mon','Tue','Wed','Thu','Fri'];
    const booked = [52,55,36,43,45];
    const completedWeekly = [43,32,34,31,36];

    const ctxBar = document.getElementById('barChart').getContext('2d');

    // small gradient for bars (optional subtle)
    const barGradA = ctxBar.createLinearGradient(0,0,0,220);
    barGradA.addColorStop(0, 'rgba(88,116,255,0.95)');
    barGradA.addColorStop(1, 'rgba(88,116,255,0.75)');

    const barGradB = ctxBar.createLinearGradient(0,0,0,220);
    barGradB.addColorStop(0, 'rgba(255,145,135,0.95)');
    barGradB.addColorStop(1, 'rgba(255,145,135,0.75)');

    const barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
        labels: days,
        datasets: [
            {
            label: 'Booked',
            data: booked,
            backgroundColor: barGradA,
            borderRadius: 8,
            barPercentage: 0.48,
            categoryPercentage: 0.7
            },
            {
            label: 'Completed',
            data: completedWeekly,
            backgroundColor: barGradB,
            borderRadius: 8,
            barPercentage: 0.48,
            categoryPercentage: 0.7
            }
        ]
        },
        options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10 } },
            tooltip: {
            backgroundColor: '#fff',
            titleColor: '#111',
            bodyColor: '#111',
            borderColor: '#eee',
            borderWidth: 1,
            }
        },
        scales: {
            x: {
            grid: { display: false },
            ticks: { color: '#6b7280' }
            },
            y: {
            beginAtZero: true,
            suggestedMax: 110,
            grid: {
                color: 'rgba(150,160,180,0.08)',
                borderDash: [4,4],
            },
            ticks: { color: '#6b7280' }
            }
        }
        }
    });

    window.addEventListener('resize', () => {
        lineAreaChart.resize();
        barChart.resize();
    });

    Array.from(document.getElementsByClassName('nav-toggle-btn')).forEach(element => {
        element.addEventListener('click', () => {
            setTimeout(() => {
                lineAreaChart.resize();
                barChart.resize();
            }, 500);
        });
    });

</script>


@endsection