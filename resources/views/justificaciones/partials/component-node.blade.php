@props(['component', 'isRoot' => false])

@php
    $type = $component->getType();
    $payload = $component->getPayload();
@endphp

@if (! $isRoot)
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $component->getLabel() }}</h4>
            <span class="text-xs font-medium tracking-widest uppercase text-gray-400 dark:text-gray-500">{{ \Illuminate\Support\Str::of($type)->replace('_', ' ')->upper() }}</span>
        </div>
        <div class="px-4 py-3 space-y-3 text-sm text-gray-700 dark:text-gray-200">
            @switch($type)
                @case('section')
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                        @foreach($payload['items'] ?? [] as $item)
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $item['label'] ?? '' }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if(($item['type'] ?? 'text') === 'link')
                                        <a href="{{ $item['value'] ?? '#' }}" target="_blank" class="text-uam-blue-500 hover:underline">
                                            {{ $item['display'] ?? $item['value'] ?? '' }}
                                        </a>
                                    @else
                                        {{ $item['value'] ?? '' }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                    @break

                @case('attachment')
                    <div class="flex items-center justify-between">
                        <span class="font-medium">{{ $payload['file_name'] ?? $component->getLabel() }}</span>
                        @if(!empty($payload['url']))
                            <a href="{{ $payload['url'] }}" target="_blank" class="text-uam-blue-500 hover:underline">{{ __('Ver') }}</a>
                        @endif
                    </div>
                    @if(!empty($payload['description']))
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payload['description'] }}</p>
                    @endif
                    @break

                @case('comment')
                    <p class="text-gray-900 dark:text-gray-100">{{ $payload['text'] ?? '' }}</p>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $payload['author'] ?? __('Sin autor') }}
                        @if(!empty($payload['created_at']))
                            · {{ $payload['created_at'] }}
                        @endif
                    </div>
                    @break

                @default
                    <pre class="text-xs bg-gray-50 dark:bg-gray-900 p-2 rounded-md overflow-x-auto">{{ json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
            @endswitch
        </div>
    </div>
@endif

@if ($component->isComposite())
    <div class="space-y-4 {{ $isRoot ? '' : 'pl-4 border-l border-gray-200 dark:border-gray-700' }}">
        @foreach($component as $child)
            @include('justificaciones.partials.component-node', ['component' => $child, 'isRoot' => false])
        @endforeach
    </div>
@endif