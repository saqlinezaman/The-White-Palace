
<?php require_once __DIR__ . '/Frontend/includes/header.php'; ?>


<!-- 404 Error Section -->
<section class=" bg-gray-50  justify-center pt-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- 404 Illustration -->
        <div class="mb-8">
            <div class="relative inline-block">
                <div class="text-9xl font-bold text-gray-300 leading-none">404</div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-32 h-32 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h4M9 7h6m-6 4h6m-6 4h6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">User Not Found</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-6">
                Sorry, the page you're looking for seems to have checked out. Don't worry, we have plenty of other rooms available for you to explore.
            </p>
            <div class="bg-white p-6 rounded-2xl shadow-lg max-w-md mx-auto">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="font-bold text-gray-900">What happened?</h3>
                        <p class="text-gray-600 text-sm">This page might have been moved, deleted, or the URL was typed incorrectly.</p>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </section>

<?php require_once __DIR__ . '/Frontend/includes/footer.php'; ?>