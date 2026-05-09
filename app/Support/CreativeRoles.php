<?php

namespace App\Support;

use Illuminate\Support\Str;

class CreativeRoles
{
    public static function options(): array
    {
        return config('creative_roles.options', []);
    }

    public static function labels(): array
    {
        return array_keys(self::options());
    }

    public static function allChoices(): array
    {
        $choices = [];

        foreach (self::options() as $label => $role) {
            $choices[] = $label;

            foreach (($role['aliases'] ?? []) as $alias) {
                $choices[] = $alias;
            }
        }

        return array_values(array_unique(array_filter($choices)));
    }

    public static function normalize(?string $role): ?string
    {
        $role = trim((string) $role);

        if ($role === '') {
            return null;
        }

        $needle = Str::lower($role);

        foreach (self::options() as $label => $data) {
            if (Str::lower($label) === $needle) {
                return $label;
            }

            foreach (($data['aliases'] ?? []) as $alias) {
                if (Str::lower($alias) === $needle) {
                    return $label;
                }
            }
        }

        return $role;
    }

    public static function display(?string $role): string
    {
        return self::normalize($role) ?? 'Creative Worker';
    }

    public static function option(?string $role): array
    {
        $normalized = self::normalize($role);

        return $normalized && isset(self::options()[$normalized])
            ? self::options()[$normalized]
            : [];
    }

    public static function aliases(?string $role): array
    {
        return self::option($role)['aliases'] ?? [];
    }

    public static function skillSuggestions(?string $role): array
    {
        return self::option($role)['skills'] ?? [];
    }

    public static function hasRole(?string $storedRole, string $selectedRole): bool
    {
        $storedNormalized = self::normalize($storedRole);
        $selectedNormalized = self::normalize($selectedRole);

        return $storedNormalized !== null && $storedNormalized === $selectedNormalized;
    }
}
