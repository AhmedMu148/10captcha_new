<x-app-layout>
    <section>
        <div class="max-w-7xl mx-auto px-4 py-12">

            <div class="grid grid-cols-12 gap-8">

                <!-- Form -->
                <div class="lg:col-span-8 lg:col-start-3 col-span-8 col-start-2">
                    <div class="bg-white rounded shadow-lg p-6">
                        <h1 class="text-left text-3xl font-semibold mb-12">Submit new ticket</h1>
                        <form action="{{ route('ticket.store') }}" method="POST">
                            @csrf
                            <div class="mb-8">
                                <label class="block mb-3 text-xl font-bold">
                                    Category:
                                </label>
                                <select name="category" class="w-full border border-gray-300 rounded-md px-4 py-3"
                                    required>
                                    <option value="">Select a category</option>
                                    <option value="support">Support</option>
                                    <option value="sales">Sales</option>
                                    <option value="general">General</option>
                                    <option value="code">Code</option>
                                    <option value="internal">Internal</option>
                                    <option value="billing">Billing</option>
                                    <option value="super_internal">Super Internal</option>
                                </select>
                            </div>

                            <div class="mb-8">
                                <label class="block mb-3 text-xl font-bold">
                                    Subject
                                </label>
                                <input type="text" name="subject"
                                    class="w-full border border-gray-300 rounded-md px-4 py-3" required>
                            </div>

                            <div class="mb-8">
                                <label class="block mb-3 text-xl font-bold">
                                    Message
                                </label>
                                <textarea name="message" class="w-full border border-gray-300 rounded-md px-4 py-3" required></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-md">
                                Send Message
                            </button>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>
</x-app-layout>
