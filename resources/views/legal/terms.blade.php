@extends('legal.layout')

@section('title', 'Termos de Uso')
@section('meta_description', 'Termos de Uso do Gabômetro — regras de contratação e uso da plataforma.')
@section('eyebrow', '[ legal ]')
@section('heading', 'Termos de Uso')
@section('updated', 'Última atualização: 13 de setembro de 2026')

@section('content')
    <p>
        Estes Termos de Uso regulam o acesso e a utilização da plataforma <strong>Gabômetro</strong>,
        software como serviço (SaaS) para processamento de resultados de simulados,
        geração de relatórios em PDF e envio de comunicações relacionadas, em benefício de escolas
        e equipes pedagógicas.
    </p>
    <p>
        Ao criar uma conta, contratar um plano ou utilizar o serviço, você concorda com estes Termos
        e com a <a href="{{ route('privacy') }}">Política de Privacidade</a>.
    </p>

    <br>

    <h2>1. Definições</h2>
    <ul>
        <li><strong>Plataforma / Serviço:</strong> o software Gabômetro, incluindo painel web, importações, geração de PDF e
            recursos associados.</li>
        <li><strong>Cliente / Escola:</strong> pessoa física ou jurídica que contrata o serviço em nome de instituição de
            ensino ou equipe pedagógica.</li>
        <li><strong>Usuário:</strong> pessoa com login na plataforma (administrador, coordenador, professor, etc.).</li>
        <li><strong>Tenant / Escola na plataforma:</strong> ambiente lógico isolado onde residem turmas, alunos, simulados e
            resultados daquela instituição.</li>
        <li><strong>Conteúdo do Cliente:</strong> dados e arquivos inseridos ou gerados a partir do uso (planilhas,
            resultados, PDFs, configurações).</li>
    </ul>

    <h2>2. Objeto do serviço</h2>
    <p>
        O Gabômetro disponibiliza ferramentas para:
    </p>
    <ul>
        <li>organização multi-escola e controle de acesso por perfis;</li>
        <li>importação de planilhas de resultados de simulados;</li>
        <li>processamento de desempenhos e geração de relatórios em PDF;</li>
        <li>envio de e-mails com resultados, inclusive via SMTP configurado pela escola;</li>
        <li>painéis e indicadores de acompanhamento pedagógico.</li>
    </ul>
    <p>
        O Serviço é oferecido da forma em que se encontra (“as is”), com evolução contínua de funcionalidades.
        Recursos em teste ou beta podem ser alterados ou descontinuados com aviso razoável quando possível.
    </p>

    <h2>3. Cadastro e elegibilidade</h2>
    <ul>
        <li>O cadastro deve conter informações verdadeiras, completas e atualizadas.</li>
        <li>Quem cria a conta declara ter legitimidade para representar a escola ou a equipe perante o Gabômetro.</li>
        <li>O Cliente é responsável por gerenciar usuários, permissões e o uso feito sob sua tenant.</li>
        <li>Credenciais de acesso são pessoais e intransferíveis; o Cliente deve zelar pela confidencialidade das senhas.
        </li>
    </ul>

    <h2>4. Planos, trial, pagamento e cancelamento</h2>
    <ul>
        <li>Os planos (limites de relatórios, recursos e preços) são os divulgados na plataforma ou em proposta comercial.
        </li>
        <li>Eventuais períodos de avaliação (trial) podem ser oferecidos a critério do Gabômetro, com ou sem necessidade de
            cartão, conforme a campanha vigente.</li>
        <li>Assinaturas recorrentes são cobradas pelo meio de pagamento disponibilizado (ex.: PIX, boleto, cartão), via
            prestador de pagamento.</li>
        <li>A falta de pagamento pode resultar em suspensão ou restrição do acesso até a regularização.</li>
        <li>O Cliente pode cancelar a renovação conforme o fluxo do painel ou canal de suporte; valores já devidos
            permanecem exigíveis, salvo política comercial diversa em vigor.</li>
        <li>Impostos e obrigações fiscais incidentes serão tratados conforme a legislação e o enquadramento do prestador;
            notas fiscais serão emitidas quando aplicável e na forma disponível.</li>
    </ul>

    <h2>5. Uso aceitável</h2>
    <p>É vedado:</p>
    <ul>
        <li>utilizar o Serviço para fins ilícitos ou em desacordo com a legislação (incluindo LGPD e normas de proteção a
            crianças e adolescentes);</li>
        <li>tentar obter acesso não autorizado a tenants, dados ou sistemas de terceiros;</li>
        <li>comprometer a estabilidade, segurança ou desempenho da plataforma (ex.: sobrecarga abusiva, engenharia reversa
            indevida);</li>
        <li>revender o Serviço ou dados nele contidos sem autorização escrita;</li>
        <li>enviar comunicações ofensivas, enganosas ou em violação a direitos de terceiros por meio dos recursos de e-mail.
        </li>
    </ul>
    <p>
        O Gabômetro pode suspender contas que violem estes Termos, mediante comunicação quando razoável e seguro fazê-lo.
    </p>

    <h2>6. Responsabilidades da Escola (Cliente)</h2>
    <ul>
        <li>Obter e manter as bases legais e autorizações necessárias para cadastrar e tratar dados de alunos e usuários.
        </li>
        <li>Garantir a qualidade e a licitude dos dados importados (planilhas, e-mails, matrículas).</li>
        <li>Configurar corretamente SMTP e conteúdos de e-mail, quando utilizar envio pela infraestrutura da escola.</li>
        <li>Orientar seus usuários sobre o uso adequado e o sigilo das informações pedagógicas.</li>
        <li>Manter software cliente, rede e acessos sob sua responsabilidade em nível razoável de segurança.</li>
    </ul>

    <h2>7. Responsabilidades do Gabômetro</h2>
    <ul>
        <li>Empregar esforços razoáveis para manter o Serviço disponível e seguro.</li>
        <li>Tratar dados pessoais conforme a Política de Privacidade e as instruções lícitas do Cliente, quando na qualidade
            de operador.</li>
        <li>Prestar suporte pelos canais oficiais, em horário e níveis compatíveis com o plano contratado.</li>
    </ul>
    <p>
        O Gabômetro não se responsabiliza por: (i) conteúdo e veracidade dos dados inseridos pela escola;
        (ii) falhas de provedores de e-mail ou SMTP da escola; (iii) indisponibilidades de terceiros
        (hospedagem, DNS, gateway de pagamento); (iv) decisões pedagógicas tomadas com base nos relatórios.
    </p>

    <h2>8. Propriedade intelectual</h2>
    <ul>
        <li>A plataforma, marcas, layout, código e documentação do Gabômetro são de nossa titularidade ou de licenciadores.
        </li>
        <li>O Cliente mantém os direitos sobre o Conteúdo do Cliente que inserir na plataforma.</li>
        <li>É concedida ao Cliente apenas licença limitada, não exclusiva e intransferível de uso do Serviço durante a
            vigência da contratação.</li>
    </ul>

    <h2>9. Proteção de dados</h2>
    <p>
        O tratamento de dados pessoais observa a
        <a href="{{ route('privacy') }}">Política de Privacidade</a>.
        Em relação aos dados de alunos, a escola reconhece seu papel de controladora e o Gabômetro o de operador,
        ressalvadas hipóteses em que a lei atribua outro enquadramento.
    </p>

    <h2>10. Limitação de responsabilidade</h2>
    <p>
        Na máxima extensão permitida pela legislação brasileira aplicável, a responsabilidade total do Gabômetro
        por danos decorrentes do Serviço fica limitada ao valor efetivamente pago pelo Cliente nos
        <strong>três (3) meses</strong> anteriores ao evento que deu causa ao dano, excluídos danos indiretos,
        lucros cessantes e perda de oportunidade, salvo dolo ou hipóteses de inafastabilidade legal
        (incluindo normas de consumo, quando aplicáveis).
    </p>

    <h2>11. Vigência e rescisão</h2>
    <ul>
        <li>Estes Termos vigem enquanto o Cliente utilizar o Serviço.</li>
        <li>Qualquer das partes pode encerrar a relação conforme o plano e a legislação.</li>
        <li>Após o encerramento, o acesso à tenant poderá ser desativado; a retenção ou exclusão de dados seguirá a Política
            de Privacidade e obrigações legais.</li>
    </ul>

    <h2>12. Alterações dos Termos</h2>
    <p>
        Podemos atualizar estes Termos para refletir mudanças do Serviço ou da lei.
        Alterações relevantes poderão ser comunicadas por e-mail cadastrado ou aviso no painel.
        O uso continuado após a entrada em vigor da nova versão implica aceitação, salvo direito de rescisão previsto em lei
        ou no plano.
    </p>

    <h2>13. Disposições gerais</h2>
    <ul>
        <li>A invalidade de alguma cláusula não prejudica as demais.</li>
        <li>A tolerância quanto ao cumprimento de qualquer condição não constitui renúncia.</li>
        <li>Estes Termos são regidos pelas leis da República Federativa do Brasil.</li>
        <li>
            Fica eleito o foro da comarca do domicílio do prestador do Serviço, ou outro legalmente imposto
            (incluindo o do consumidor, quando aplicável), para dirimir controvérsias.
        </li>
    </ul>

    <h2>14. Contato</h2>
    <p>
        Suporte e questões contratuais:
        <a href="mailto:contato@gabometro.com.br">contato@gabometro.com.br</a><br>
        Privacidade:
        <a href="mailto:privacidade@gabometro.com.br">privacidade@gabometro.com.br</a>
    </p>
@endsection
