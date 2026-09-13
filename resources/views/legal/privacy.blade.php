@extends('legal.layout')

@section('title', 'Política de Privacidade')
@section('meta_description',
    'Política de Privacidade do Gabômetro — como tratamos dados de escolas, usuários e
    alunos.')
@section('eyebrow', '[ legal ]')
@section('heading', 'Política de Privacidade')
@section('updated', 'Última atualização: 13 de setembro de 2026')

@section('content')
    <p>
        Esta Política de Privacidade descreve como o <strong>Gabômetro</strong> (“nós”, “nossa plataforma”)
        coleta, utiliza, armazena e compartilha informações no âmbito da prestação do serviço de
        geração de relatórios de desempenho de simulados escolares.
    </p>
    <p>
        Ao utilizar a plataforma, você declara ter lido e compreendido esta Política.
        Em caso de dúvida, contate <a href="mailto:privacidade@gabometro.com.br">privacidade@gabometro.com.br</a>.
    </p>

    <br>

    <h2>1. Quem somos</h2>
    <p>
        O Gabômetro é um software como serviço (SaaS) voltado a escolas e equipes pedagógicas,
        permitindo importação de resultados de simulados, geração de relatórios em PDF e envio
        por e-mail aos alunos, com painel multi-escola (multi-tenant).
    </p>
    <p>
        <strong>Canal de privacidade:</strong>
        <a href="mailto:privacidade@gabometro.com.br">privacidade@gabometro.com.br</a>
    </p>
    <p>
        Os dados de identificação do prestador (nome/razão social, CPF ou CNPJ, endereço)
        serão os constantes no cadastro fiscal e nos Termos de Uso vigentes no momento da contratação.
    </p>

    <h2>2. Papéis na LGPD</h2>
    <ul>
        <li>
            <strong>Escola contratante:</strong> em regra, atua como <strong>controladora</strong> dos dados
            pessoais dos alunos e responsáveis que ela própria cadastra ou importa na plataforma.
        </li>
        <li>
            <strong>Gabômetro:</strong> atua como <strong>operador</strong> desses dados, tratando-os
            para prestar o serviço contratado, segundo as instruções da escola e esta Política.
        </li>
        <li>
            Em relação aos dados da conta de acesso (coordenadores, professores, administradores),
            o Gabômetro pode atuar como controlador para gestão da conta, autenticação, cobrança e suporte.
        </li>
    </ul>
    <p>
        A escola é responsável por garantir base legal e autorizações necessárias
        (incluindo dados de crianças e adolescentes) para inserir informações na plataforma.
    </p>

    <h2>3. Quais dados tratamos</h2>
    <p>Podemos tratar as seguintes categorias, conforme o uso do serviço:</p>
    <ul>
        <li><strong>Dados de conta:</strong> nome, e-mail, senha (armazenada de forma criptografada/hash), perfil/função
            (admin, coordenador, professor).</li>
        <li><strong>Dados da escola:</strong> nome, identificadores cadastrais eventualmente informados (ex.: CNPJ),
            contatos, configurações de SMTP e preferências.</li>
        <li><strong>Dados de alunos inseridos pela escola:</strong> matrícula, nome, e-mail institucional ou informado,
            turma, resultados de simulados, acertos por disciplina e demais campos da planilha importada.</li>
        <li><strong>Arquivos gerados:</strong> PDFs de relatório de desempenho e registros de status de envio de e-mail.
        </li>
        <li><strong>Dados técnicos:</strong> endereço IP, logs de acesso, data/hora de uso, identificadores de sessão e
            informações de dispositivo, para segurança e diagnóstico.</li>
        <li><strong>Dados de pagamento:</strong> processados por gateway de pagamento. <strong>Não armazenamos</strong> o
            número completo do cartão de crédito; tokenização e cobrança ficam a cargo do prestador de pagamento.</li>
    </ul>

    <h2>4. Finalidades do tratamento</h2>
    <ul>
        <li>Criar e gerenciar contas, escolas (tenants) e permissões de acesso;</li>
        <li>Importar planilhas, processar resultados e gerar relatórios em PDF;</li>
        <li>Enviar e-mails de resultado conforme configuração da escola (incluindo SMTP próprio da escola, quando
            aplicável);</li>
        <li>Prestar suporte técnico e melhorar a estabilidade do serviço;</li>
        <li>Cumprir obrigações legais, regulatórias e de cobrança da assinatura;</li>
        <li>Prevenir fraude, abuso e incidentes de segurança.</li>
    </ul>
    <p>
        <strong>Não vendemos</strong> dados pessoais a terceiros e não utilizamos dados de alunos
        para publicidade de terceiros.
    </p>

    <h2>5. Bases legais (LGPD)</h2>
    <p>O tratamento fundamenta-se, conforme o caso, em:</p>
    <ul>
        <li>execução de contrato ou procedimentos preliminares (art. 7º, V);</li>
        <li>cumprimento de obrigação legal ou regulatória (art. 7º, II);</li>
        <li>legítimo interesse, quando aplicável e ponderado (art. 7º, IX), por exemplo para segurança da plataforma;</li>
        <li>para dados de crianças e adolescentes, o disposto no art. 14 da LGPD, cabendo à escola as autorizações e deveres
            perante alunos e responsáveis.</li>
    </ul>

    <h2>6. Com quem compartilhamos</h2>
    <p>Podemos compartilhar dados estritamente necessários com:</p>
    <ul>
        <li><strong>Infraestrutura e hospedagem</strong> (servidores, armazenamento, filas);</li>
        <li><strong>Gateway de pagamento</strong> (assinaturas e cobranças);</li>
        <li><strong>Provedores de e-mail</strong> quando o envio não for exclusivamente pelo SMTP da escola;</li>
        <li>Autoridades públicas, quando houver obrigação legal.</li>
    </ul>
    <p>
        Esses prestadores atuam como operadores ou equivalentes, sob obrigações de segurança e confidencialidade.
    </p>

    <h2>7. Cookies e tecnologias semelhantes</h2>
    <p>
        Utilizamos cookies e tecnologias essenciais ao funcionamento do site e do painel
        (por exemplo: sessão de autenticação, segurança CSRF e preferências básicas).
    </p>
    <p>
        Caso passemos a utilizar cookies de analytics ou marketing de terceiros,
        solicitaremos consentimento quando exigido e atualizaremos esta Política,
        indicando quais ferramentas são usadas e como gerenciar preferências.
    </p>

    <h2>8. Armazenamento, retenção e exclusão</h2>
    <ul>
        <li>Os dados são armazenados em ambiente controlado, com acesso restrito e segregação por escola (multi-tenant), na
            medida da arquitetura do produto.</li>
        <li>Mantemos os dados enquanto a conta/escola estiver ativa e pelo período necessário ao serviço e a obrigações
            legais.</li>
        <li>Após cancelamento, poderemos reter dados pelo prazo necessário à defesa de direitos, cumprimento legal ou
            exclusão segura em rotinas de backup.</li>
        <li>A escola pode solicitar exportação ou exclusão de dados de sua tenant, observados limites técnicos e legais.
        </li>
    </ul>

    <h2>9. Segurança</h2>
    <p>Adotamos medidas técnicas e organizacionais razoáveis, incluindo:</p>
    <ul>
        <li>comunicação criptografada (HTTPS);</li>
        <li>controle de acesso por perfil (ex.: professores com permissão de leitura);</li>
        <li>isolamento lógico de dados por escola;</li>
        <li>senhas armazenadas com hash;</li>
        <li>processamento de pagamentos por intermediário especializado.</li>
    </ul>
    <p>
        Nenhum sistema é 100% isento de risco. Em caso de incidente relevante que afete dados pessoais,
        adotaremos medidas de contenção e comunicação conforme a LGPD.
    </p>

    <h2>10. Direitos do titular</h2>
    <p>Nos termos da LGPD, titulares podem solicitar:</p>
    <ul>
        <li>confirmação de tratamento e acesso;</li>
        <li>correção de dados incompletos ou desatualizados;</li>
        <li>anonimização, bloqueio ou eliminação de dados desnecessários;</li>
        <li>portabilidade, quando aplicável;</li>
        <li>informação sobre compartilhamentos;</li>
        <li>revogação de consentimento, quando essa for a base legal.</li>
    </ul>
    <p>
        Para dados de alunos tratados sob instrução da escola, o canal prioritário é a própria escola.
        Também atendemos solicitações em
        <a href="mailto:privacidade@gabometro.com.br">privacidade@gabometro.com.br</a>,
        podendo ser necessário validar identidade e o vínculo com a controladora.
    </p>

    <h2>11. Transferências internacionais</h2>
    <p>
        Caso dados sejam processados ou armazenados fora do Brasil (por exemplo, provedores de nuvem),
        observaremos as regras da LGPD sobre transferências internacionais e salvaguardas adequadas.
    </p>

    <h2>12. Alterações</h2>
    <p>
        Esta Política pode ser atualizada para refletir mudanças no serviço ou na legislação.
        A data de “última atualização” será revisada e, quando a mudança for relevante,
        poderemos notificar usuários cadastrados por e-mail ou aviso no painel.
    </p>

    <h2>13. Contato</h2>
    <p>
        Dúvidas sobre privacidade:
        <a href="mailto:privacidade@gabometro.com.br">privacidade@gabometro.com.br</a>
    </p>
@endsection
