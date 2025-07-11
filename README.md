# 🧩 WJM Contact Form Plugin

## ✍️ Autor
Welington Jose Miyazato
🔗 WJM Digital
🎓 Zend Certified Engineer | Fullstack DevOps | Digital Entrepreneur
📍 Japão 🇯🇵

Plugin robusto de formulário de contato para WordPress, com arquitetura moderna (DDD, MVC, Clean Architecture), suporte a múltiplos formulários personalizados, mensagens armazenadas no banco de dados, e interface amigável baseada em Bootstrap.

---

## 🚀 Funcionalidades

- ✅ Criação de múltiplos formulários com campos dinâmicos
- ✅ Suporte a: text, textarea, email, checkbox, radio, select, file, date, datetime-local
- ✅ Validação de campos obrigatórios
- ✅ Customização de mensagens de erro/sucesso e texto do botão
- ✅ Estilo visual baseado no Bootstrap
- ✅ Shortcode automático para embutir formulários
- ✅ Listagem de formulários com ações (editar, excluir, copiar shortcode)
- ✅ Armazenamento das mensagens no banco de dados
- ✅ Visualização de mensagens recebidas com:
  - Paginação
  - Filtro por campo (ex: nome, data)
  - Exportação CSV/Excel
  - Log de visualização

---

## 🧠 Arquitetura

- 🧩 **DDD + MVC + Clean Architecture + SRA**
- 📦 Camadas separadas: Domain, Application, Infra, Views
- 🧪 Testes com PHPUnit (Unit + Integração)
- 🔄 Injeção de dependência
- 🗂️ DTOs (Data Transfer Objects) para casos de uso
- 🧱 Repositórios e interfaces desacopladas

---

## 📁 Estrutura de Diretórios

```bash
src/
├── Application/
│   ├── Controller/
│   │   ├── DashboardController.php
│   │   ├── FormController.php
│   │   ├── SubmissionController.php
│   │   └── ExportController.php
│   ├── Handlers/
│   │   └── FormSubmissionHandler.php
│   ├── UseCase/
│   │   └── Form/
│   │       ├── DTO/
│   │       │   ├── CreateFormDTO.php
│   │       │   ├── UpdateFormDTO.php
│   │       │   └── SubmissionDTO.php
│   │       ├── CreateFormUseCase.php
│   │       ├── UpdateFormUseCase.php
│   │       └── SubmitFormUseCase.php
├── Domain/
│   ├── Entities/
│   │   ├── Form.php
│   │   ├── FormField.php
│   │   └── FormMessage.php
│   ├── Repositories/
│   │   └── FormRepositoryInterface.php
│   └── ValueObjects/
│       └── Email.php
├── Infra/
│   ├── Database/
│   │   └── FormTableMigrator.php
│   ├── Hooks/
│   │   └── ShortcodeHandler.php
│   ├── Repositories/
│   │   └── FormRepository.php
│   ├── Services/
│   │   ├── EmailSender.php
│   │   └── ExportService.php
│   └── WordPress/
│       ├── View.php
│       ├── AdminMenu.php
│       ├── AssetsLoader.php
│       └── Form/
│           └── FormRenderer.php
├── Validators/
│   └── FormValidator.php
├── views/
│   └── admin/
│       ├── dashboard.php
│       ├── form-editor.php
│       ├── messages.php
│       └── parts/
│           ├── forms-table.php
│           └── messages-table.php
├── PluginKernel.php
└── wjm-contact-form.php
```

