# Graph Report - .  (2026-10-14)

## Corpus Check
- Large corpus: 597 files · ~455,132 words. Semantic extraction will be expensive (many Claude tokens). Consider running on a subfolder.

## Summary
- 84 nodes · 91 edges · 20 communities (16 shown, 4 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- [[_COMMUNITY_Arranque y Fortify|Arranque y Fortify]]
- [[_COMMUNITY_Registro y contraseñas|Registro y contraseñas]]
- [[_COMMUNITY_Modelo User|Modelo User]]
- [[_COMMUNITY_Factory de usuarios|Factory de usuarios]]
- [[_COMMUNITY_Cierre de sesión|Cierre de sesión]]
- [[_COMMUNITY_Seeders|Seeders]]
- [[_COMMUNITY_Reglas de perfil|Reglas de perfil]]
- [[_COMMUNITY_Cliente IA Oráculo|Cliente IA Oráculo]]
- [[_COMMUNITY_Excepción del Oráculo|Excepción del Oráculo]]
- [[_COMMUNITY_Modelo Vehicle|Modelo Vehicle]]
- [[_COMMUNITY_Controlador base|Controlador base]]

## God Nodes (most connected - your core abstractions)
1. `User` - 7 edges
2. `FortifyServiceProvider` - 7 edges
3. `CreateNewUser` - 5 edges
4. `AppServiceProvider` - 5 edges
5. `UserFactory` - 5 edges
6. `ResetUserPassword` - 4 edges
7. `DatabaseSeeder` - 4 edges
8. `profileRules()` - 3 edges
9. `static` - 3 edges
10. `User` - 2 edges

## Surprising Connections (you probably didn't know these)
- None detected - all connections are within the same source files.

## Import Cycles
- None detected.

## Communities (20 total, 4 thin omitted)

### Community 0 - "Arranque y Fortify"
Cohesion: 0.23
Nodes (3): AppServiceProvider, FortifyServiceProvider, ServiceProvider

### Community 1 - "Registro y contraseñas"
Cohesion: 0.27
Nodes (8): User, User, CreatesNewUsers, CreateNewUser, ResetUserPassword, PasswordValidationRules, ProfileValidationRules, ResetsUserPasswords

### Community 2 - "Modelo User"
Cohesion: 0.36
Nodes (5): Authenticatable, HasFactory, User, MustVerifyEmail, Notifiable

### Community 3 - "Factory de usuarios"
Cohesion: 0.43
Nodes (3): UserFactory, Factory, static

### Community 4 - "Cierre de sesión"
Cohesion: 0.60
Nodes (3): Logout, Redirector, RedirectResponse

### Community 5 - "Seeders"
Cohesion: 0.60
Nodes (3): Seeder, DatabaseSeeder, WithoutModelEvents

### Community 6 - "Reglas de perfil"
Cohesion: 0.83
Nodes (3): emailRules(), nameRules(), profileRules()

## Knowledge Gaps
- **1 isolated node(s):** `Controller`
  These have ≤1 connection - possible missing edges or undocumented components.
- **4 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **What connects `Controller` to the rest of the system?**
  _1 weakly-connected nodes found - possible documentation gaps or missing edges._