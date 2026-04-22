# TODO: Make Register Design Consistent with Dashboard

## Approved Plan Steps

### 1. [✅] Create TODO.md 
### 2. [✅] Update resources/views/auth/register-role.blade.php 
   - Add dashboard-style navbar (glassmorphism) ✅
   - Add hero header matching dashboard ✅
   - Upgrade role cards to dashboard stats style (rounded-[2.5rem], hover:shadow-2xl) ✅
   - Add gradient accents and role-specific icons ✅
   - Style buttons with dashboard gradient hover effects ✅
   - Use #F8FAFC background, max-w-7xl layout ✅
### 3. [ ] Minor updates to resources/views/auth/register.blade.php
   - Unify navbar style to match new dashboard nav
   - Remove purple theming, use consistent blue #2563EB for both roles
   - Ensure rounded-[2.5rem] cards and shadows match
### 4. [ ] Test changes
   - Clear view cache: `php artisan view:clear`
   - Visit http://konekin.test/register and http://konekin.test/register/role
   - Verify visual consistency with dashboard
### 5. [ ] Update TODO.md with completion status
### 6. [ ] Attempt completion

**Next step:** Step 3 - Minor updates to register.blade.php (unify navbar, remove purple theming)

---

*Updated: After each step completion*
