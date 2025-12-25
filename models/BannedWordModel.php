<?php

class BannedWordModel
{
    private string $file;

    public function __construct()
    {
        $dir = __DIR__ . '/../data';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $this->file = $dir . '/banned_words.json';
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    public function all(): array
    {
        $raw = @file_get_contents($this->file);
        $data = $raw ? json_decode($raw, true) : [];
        if (!is_array($data)) return [];
        return array_values(array_filter(array_map(fn($w) => trim((string)$w), $data)));
    }

    public function add(string $word): void
    {
        $word = trim(mb_strtolower($word));
        if ($word === '') return;
        $words = $this->all();
        if (!in_array($word, $words, true)) {
            $words[] = $word;
            $this->save($words);
        }
    }

    public function remove(string $word): void
    {
        $word = trim(mb_strtolower($word));
        $words = array_filter($this->all(), fn($w) => $w !== $word);
        $this->save($words);
    }

    public function containsBannedWord(string $text): bool
    {
        $words = $this->all();
        if (empty($words)) return false;
        $lower = mb_strtolower($text);
        foreach ($words as $w) {
            if ($w !== '' && mb_strpos($lower, $w) !== false) return true;
        }
        return false;
    }

    private function save(array $words): void
    {
        file_put_contents($this->file, json_encode(array_values($words), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

?>