<?php
include '../Frontend/includes/header.php';

?>
 <div class="hero bg-base-200 min-h-screen relative" style="background-image: url(assets/images/hero-image-2.jpg);">
        <!-- Light overlay -->
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="hero-content flex-col lg:flex-row gap-8 relative z-10 w-full max-w-7xl mx-auto px-4">
            <!-- Left Content -->
            <div class="text-center lg:text-left w-full lg:w-1/2 text-white">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
                    Welcome to <br> The White Palace
                </h1>
                <p class="py-6 text-sm md:text-base lg:text-lg">
                    Experience luxury and comfort at its finest. Book your perfect stay with us and create unforgettable memories in our world-class hospitality.
                </p>
                <button class="btn btn-success">Read more</button>
            </div>

            <!-- Right Form -->
            <div class="w-full lg:w-5/12 max-w-md">
                <div class="card bg-base-100 shadow-2xl">
                    <div class="card-body p-4 md:p-5">
                        <h2 class="text-xl font-bold text-center mb-4">Book Your Stay</h2>
                        <form class="space-y-3">
                            <!-- Name Field -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold w-24 flex-shrink-0">Full Name</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    class="input input-bordered w-full focus:input-primary" 
                                    placeholder="Enter your full name" 
                                    required
                                />
                            </div>

                            <!-- Email Field -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold w-24 flex-shrink-0">Email</span>
                                </label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="input input-bordered w-full focus:input-primary" 
                                    placeholder="Enter your email address" 
                                    required
                                />
                            </div>
                            <!-- Number Field -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold w-24 flex-shrink-0">Number</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="number" 
                                    class="input input-bordered w-full focus:input-primary" 
                                    placeholder="Enter your Phone number" 
                                    required
                                />
                            </div>

                            <!-- Room Selection -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold w-24 flex-shrink-0">Room Type</span>
                                </label>
                                <select class="select select-bordered w-full focus:select-primary" name="room_type" required>
                                    <option disabled selected>Select room type</option>
                                    <option value="single">Single Room (1 Person)</option>
                                    <option value="double">Double Room (2 Persons)</option>
                                    <option value="family">Family Suite (3-4 Persons)</option>
                                    <option value="deluxe">Deluxe Suite (2 Persons)</option>
                                </select>
                            </div>

                            <!-- Date Fields Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold w-24 flex-shrink-0">Check In</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        name="checkin" 
                                        class="input input-bordered w-full focus:input-primary" 
                                        required
                                    />
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold w-24 flex-shrink-0">Check Out</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        name="checkout" 
                                        class="input input-bordered w-full focus:input-primary" 
                                        required
                                    />
                                </div>
                            </div>



                            <!-- Submit Button -->
                            <div class="form-control mt-4">
                                <button type="submit" class="btn btn-success w-full text-white font-semibold py-2 hover:btn-primary-focus transition-all duration-200">
                                    Book Appointment
                                </button>
                            </div>

                            <!-- Additional Info -->
                            <div class="text-center mt-3">
                                <p class="text-sm text-gray-600">
                                    Need help? <a href="#" class="link link-primary font-semibold">Contact us</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../Frontend/includes/footer.php';?>