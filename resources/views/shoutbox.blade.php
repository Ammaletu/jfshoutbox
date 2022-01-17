<x-app-layout>
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                @endif
            @endauth
        </div>
    @endif
    
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-16">
        <h1 class="text-3xl mt-4">{{ config('app.shoutbox_question', 'How many roads must a man walk down?') }}</h1>

        @if ($message = Session::get('success'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-8">
                <p>{{ $message }}</p>
            </div>
        @endif
        
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-y-auto shadow sm:rounded-lg max-h-[500px]">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                    <ul id="shoutbox_list">
                        <li class="template mb-6 hidden clear-right border-b-neutral-200 relative">
                            <strong>#{NO}. {NICKNAME} sagt:</strong>
                            <div class="answerText mt-1">{ANSWER}</div>
                            @auth
                                <div class="absolute -right-8 top-0">
                                    <button type="button" class="block-button mr-3 text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Sperren</button>
                                    <button type="button" class="release-button mr-3 text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Freigeben</button>   
                                </div>
                            @endauth
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l justify-center">
                    <form action="{{ route('answers.store') }}" method="POST" id="shoutbox_form">
                        <h2 class="text-2xl">Mach mit!</h2>
                        
                        @csrf
                        
                        <div class="form-group mt-6">
                            <label for="answer">Dein Name</label>
                            <input name="nickname" class="bg-gray-100 rounded border border-gray-400 leading-normal w-full h-7 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white" 
                                    id="nickname" value="" maxlength="64" />
                            @if ($errors->has('nickname'))
                                <span class="form-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ $errors->first('nickname') }}</span>
                            @endif
                        </div>
                        
                        <div class="form-group mt-6">
                            <label for="answer">Text der Antwort</label>
                            <textarea name="answer" class="bg-gray-100 rounded border border-gray-400 leading-normal w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white"
                                    id="answer" maxlength="250"></textarea>
                            @if ($errors->has('answer'))
                                <span class="form-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ $errors->first('answer') }}</span>
                            @endif
                        </div>

                        <input name="telephone" class="hidden bg-gray-100 rounded border border-gray-400 leading-normal w-full h-7 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white" value="" placeholder="Bitte leer lassen, Spamschutz" />
                        
                        <div class="form-group mt-6">
                            <div class="float-right">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Antwort abschicken</button>
                            </div>
                            <div class="clear-both"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
