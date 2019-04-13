<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\Acronym;

class AcronymSeeder extends Seeder
{
    /**
     * Seed the acronyms table.
     *
     * @link http://www.prociv.pt/bk/PROTECAOCIVIL/LEGISLACAONORMATIVOS/Directivas/ANPC_DON-1_DIOPS.pdf
     *
     * @return void
     */
    public function run(): void
    {
        $acronyms = [
            [
                'initials' => 'AA',
                'meaning'  => 'Área de Atuação',
            ],
            [
                'initials' => 'AD',
                'meaning'  => 'Apoio Direto',
            ],
            [
                'initials' => 'AFN',
                'meaning'  => 'Autoridade Florestal Nacional',
            ],

            [
                'initials' => 'AHB',
                'meaning'  => 'Associações Humanitárias de Bombeiros',
            ],

            [
                'initials' => 'ANAC',
                'meaning'  => 'Autoridade Nacional da Aviação Civil',
            ],

            [
                'initials' => 'ANACOM',
                'meaning'  => 'Autoridade Nacional de Comunicações',
            ],

            [
                'initials' => 'ANAFRE',
                'meaning'  => 'Associação Nacional de Freguesias',
            ],

            [
                'initials' => 'ANBP',
                'meaning'  => 'Associação Nacional de Bombeiros Profissionais',
            ],

            [
                'initials' => 'ANMP',
                'meaning'  => 'Associação Nacional de Municípios Portugueses',
            ],

            [
                'initials' => 'ANEPC',
                'meaning'  => 'Autoridade Nacional de Emergência e Proteção Civil',
            ],

            [
                'initials' => 'ANSR',
                'meaning'  => 'Autoridade Nacional de Segurança Rodoviária',
            ],

            [
                'initials' => 'APC',
                'meaning'  => 'Agentes de Protecção Civil',
            ],

            [
                'initials' => 'ASAE',
                'meaning'  => 'Agência para a Segurança Alimentar',
            ],

            [
                'initials' => 'ATA',
                'meaning'  => 'Ataque Ampliado',
            ],

            [
                'initials' => 'ATI',
                'meaning'  => 'Ataque Inicial',
            ],

            [
                'initials' => 'AVATA',
                'meaning'  => 'Aviões de Ataque Ampliado',
            ],

            [
                'initials' => 'AVATI',
                'meaning'  => 'Aviões de Ataque Inicial',
            ],

            [
                'initials' => 'AVBM',
                'meaning'  => 'Avião Bombardeiro Médio',
            ],

            [
                'initials' => 'AVBP',
                'meaning'  => 'Avião Bombardeiro Pesado',
            ],

            [
                'initials' => 'BAL',
                'meaning'  => 'Base de Apoio Logístico',
            ],

            [
                'initials' => 'BCIN',
                'meaning'  => 'Brigada de Combate a Incêndios',
            ],

            [
                'initials' => 'BHATI',
                'meaning'  => 'Brigada Helitransportada de Ataque Inicial',
            ],

            [
                'initials' => 'BHSP',
                'meaning'  => 'Base de Helicópteros em Serviço Permanente',
            ],

            [
                'initials' => 'BSB',
                'meaning'  => 'Batalhão de Sapadores Bombeiros',
            ],

            [
                'initials' => 'BSF',
                'meaning'  => 'Brigada de Sapadores Florestais',
            ],

            [
                'initials' => 'BTO',
                'meaning'  => 'Briefing Técnico Operacional',
            ],

            [
                'initials' => 'CADIS',
                'meaning'  => 'Comandante Operacional de Agrupamento Distrital',
            ],

            [
                'initials' => 'CAS',
                'meaning'  => 'Comandante de Assistência',
            ],

            [
                'initials' => 'CB',
                'meaning'  => 'Corpo de Bombeiros',
            ],

            [
                'initials' => 'CCB',
                'meaning'  => 'Comandantes dos Corpos de Bombeiros',
            ],

            [
                'initials' => 'CCBS',
                'meaning'  => 'Centros de Coordenação de Busca e Salvamento',
            ],

            [
                'initials' => 'CCO',
                'meaning'  => 'Centros de Coordenação Operacional',
            ],

            [
                'initials' => 'CCOD',
                'meaning'  => 'Centro de Coordenação Operacional Distrital',
            ],

            [
                'initials' => 'CCOM',
                'meaning'  => 'Comando Conjunto para as Operações Militares',
            ],

            [
                'initials' => 'CCON',
                'meaning'  => 'Centro de Coordenação Operacional Nacional',
            ],

            [
                'initials' => 'CDOS',
                'meaning'  => 'Comando Distrital de Operações de Socorro',
            ],

            [
                'initials' => 'CDPC',
                'meaning'  => 'Comissão Distrital de Proteção Civil',
            ],

            [
                'initials' => 'CELCOM',
                'meaning'  => 'Célula Operacional de Logística e Comunicações do CNOS',
            ],

            [
                'initials' => 'CETAC',
                'meaning'  => 'Centro Tático de Comando',
            ],

            [
                'initials' => 'CM',
                'meaning'  => 'Câmaras Municipais',
            ],

            [
                'initials' => 'CMA',
                'meaning'  => 'Centros de Meios Aéreos',
            ],

            [
                'initials' => 'CMDF',
                'meaning'  => 'Comissão Municipal de Defesa da Floresta',
            ],

            [
                'initials' => 'CMPC',
                'meaning'  => 'Comissão Municipal de Protecção Civil',
            ],

            [
                'initials' => 'CNAF',
                'meaning'  => 'Corpo Nacional de Agentes Florestais',
            ],

            [
                'initials' => 'CNOS',
                'meaning'  => 'Comando Nacional de Operações de Socorro',
            ],

            [
                'initials' => 'CNPC',
                'meaning'  => 'Comissão Nacional de Protecção Civil',
            ],

            [
                'initials' => 'COC',
                'meaning'  => 'Comando Operacional Conjunto',
            ],

            [
                'initials' => 'CODIS',
                'meaning'  => 'Comandante Operacional Distrital',
            ],

            [
                'initials' => 'COM',
                'meaning'  => 'Comandante Operacional Municipal',
            ],

            [
                'initials' => 'CONAC',
                'meaning'  => 'Comandante Operacional Nacional',
            ],

            [
                'initials' => 'COPAR',
                'meaning'  => 'Coordenador de Operações Aéreas',
            ],

            [
                'initials' => 'COS',
                'meaning'  => 'Comandante das Operações de Socorro',
            ],

            [
                'initials' => 'CPO',
                'meaning'  => 'Comandante de Permanência às Operações',
            ],

            [
                'initials' => 'CRIF',
                'meaning'  => 'Companhia de Reforço para Incêndios Florestais',
            ],

            [
                'initials' => 'CTO',
                'meaning'  => 'Comunicado Técnico Operacional',
            ],

            [
                'initials' => 'CVP',
                'meaning'  => 'Cruz Vermelha Portuguesa',
            ],

            [
                'initials' => 'DECIF',
                'meaning'  => 'Dispositivo Especial de Combate a Incêndios Florestais',
            ],

            [
                'initials' => 'DFCI',
                'meaning'  => 'Defesa da Floresta Contra Incêndios',
            ],

            [
                'initials' => 'DGAM',
                'meaning'  => 'Direcção-Geral da Autoridade Marítima',
            ],

            [
                'initials' => 'DGPCE',
                'meaning'  => 'Direção-Geral de Proteção Civil e Emergências',
            ],

            [
                'initials' => 'DGS',
                'meaning'  => 'Direcção-Geral da Saúde',
            ],

            [
                'initials' => 'DIOPS',
                'meaning'  => 'Dispositivo Integrado das Operações de Protecção e Socorro',
            ],

            [
                'initials' => 'DIPE',
                'meaning'  => 'Dispositivo Integrado de Prevenção Estrutural',
            ],

            [
                'initials' => 'DON',
                'meaning'  => 'Directiva Operacional Nacional',
            ],

            [
                'initials' => 'EAE',
                'meaning'  => 'Estado de Alerta Especial',
            ],

            [
                'initials' => 'EAP',
                'meaning'  => 'Equipa de Apoio Psicossocial',
            ],

            [
                'initials' => 'ECIN',
                'meaning'  => 'Equipa de Combate a Incêndios Florestais',
            ],

            [
                'initials' => 'EDP',
                'meaning'  => 'Energias de Portugal',
            ],

            [
                'initials' => 'EGAUF',
                'meaning'  => 'Equipa de Grupo de Análise e Uso do Fogo',
            ],

            [
                'initials' => 'EHATI',
                'meaning'  => 'Equipa Helitransportada de Ataque Inicial',
            ],

            [
                'initials' => 'EIP',
                'meaning'  => 'Equipas de Intervenção Permanentes',
            ],

            [
                'initials' => 'ELAC',
                'meaning'  => 'Equipa Logística de Apoio ao Combate',
            ],

            [
                'initials' => 'EMA',
                'meaning'  => 'Empresa de Meios Aéreos',
            ],

            [
                'initials' => 'EMEIF',
                'meaning'  => 'Equipa de Manutenção e Exploração de Informação Florestal',
            ],

            [
                'initials' => 'EMGFA',
                'meaning'  => 'Estado-Maior General das Forças Armadas',
            ],

            [
                'initials' => 'EMIF',
                'meaning'  => 'Equipa Municipal de Intervenção Florestal',
            ],

            [
                'initials' => 'EOBS',
                'meaning'  => 'Equipas de Observação',
            ],

            [
                'initials' => 'EP',
                'meaning'  => 'Estradas de Portugal',
            ],

            [
                'initials' => 'EPCO',
                'meaning'  => 'Equipa de Posto de Comando Operacional',
            ],

            [
                'initials' => 'ERAS',
                'meaning'  => 'Equipas de Reconhecimento e Avaliação da Situação',
            ],

            [
                'initials' => 'ERCC',
                'meaning'  => 'Emergency Response Coordination Centre',
            ],

            [
                'initials' => 'ESF',
                'meaning'  => 'Equipa de Sapadores Florestais',
            ],

            [
                'initials' => 'FA',
                'meaning'  => 'Forças Armadas',
            ],

            [
                'initials' => 'FAP',
                'meaning'  => 'Força Aérea Portuguesa',
            ],

            [
                'initials' => 'FEB',
                'meaning'  => 'Força Especial de Bombeiros',
            ],

            [
                'initials' => 'FFAA',
                'meaning'  => 'Forças Armadas',
            ],

            [
                'initials' => 'GAUF',
                'meaning'  => 'Grupo de Análise e Uso do Fogo',
            ],

            [
                'initials' => 'GCIF',
                'meaning'  => 'Grupo de Combate a Incêndios Florestais',
            ],

            [
                'initials' => 'GIPE',
                'meaning'  => 'Grupo de Intervenção Permanente',
            ],

            [
                'initials' => 'GIPS',
                'meaning'  => 'Grupo de Intervenção de Proteção e Socorro da GNR',
            ],

            [
                'initials' => 'GLOR',
                'meaning'  => 'Grupo Logístico de Reforço',
            ],

            [
                'initials' => 'GNR',
                'meaning'  => 'Guarda Nacional Republicana',
            ],

            [
                'initials' => 'GREL',
                'meaning'  => 'Grupo de Reforço Ligeiro',
            ],

            [
                'initials' => 'GRIF',
                'meaning'  => 'Grupo de Reforço para Combate a Incêndios Florestais',
            ],

            [
                'initials' => 'GRUATA',
                'meaning'  => 'Grupo de Reforço para Ataque Ampliado',
            ],

            [
                'initials' => 'GTF',
                'meaning'  => 'Gabinete Técnico Florestal',
            ],

            [
                'initials' => 'HEATA',
                'meaning'  => 'Helicópteros de Ataque Ampliado',
            ],

            [
                'initials' => 'HEATI',
                'meaning'  => 'Helicópteros de Ataque Inicial',
            ],

            [
                'initials' => 'HEB',
                'meaning'  => 'Helicóptero Bombardeiro',
            ],

            [
                'initials' => 'HEBL',
                'meaning'  => 'Helicóptero Bombardeiro Ligeiro',
            ],

            [
                'initials' => 'HEBM',
                'meaning'  => 'Helicóptero Bombardeiro Médio',
            ],

            [
                'initials' => 'HEBP',
                'meaning'  => 'Helicóptero Bombardeiro Pesado',
            ],

            [
                'initials' => 'HESA',
                'meaning'  => 'Helicóptero de Socorro e Assistência',
            ],

            [
                'initials' => 'ICNB',
                'meaning'  => 'Instituto de Conservação da Natureza e da Biodiversidade',
            ],

            [
                'initials' => 'ICNF',
                'meaning'  => 'Instituto de Conservação da Natureza e das Florestas',
            ],

            [
                'initials' => 'IFN',
                'meaning'  => 'Inventário Florestal Nacional',
            ],

            [
                'initials' => 'IM',
                'meaning'  => 'Instituto de Meteorologia',
            ],

            [
                'initials' => 'IML',
                'meaning'  => 'Instituto de Medicina Legal',
            ],

            [
                'initials' => 'INAC',
                'meaning'  => 'Instituto Nacional de Aviação Civil',
            ],

            [
                'initials' => 'INEM',
                'meaning'  => 'Instituto Nacional de Emergência Médica',
            ],

            [
                'initials' => 'INRI',
                'meaning'  => 'Instituto de Infra-Estruturas Rodoviárias',
            ],

            [
                'initials' => 'INSTROP',
                'meaning'  => 'Instrução operacional',
            ],

            [
                'initials' => 'IPMA',
                'meaning'  => 'Instituto Português do Mar e da Atmosfera',
            ],

            [
                'initials' => 'IPTM',
                'meaning'  => 'Instituto Portuário e dos Transportes Marítimos',
            ],

            [
                'initials' => 'ISS',
                'meaning'  => 'Instituto de Segurança Social',
            ],

            [
                'initials' => 'ITG',
                'meaning'  => 'Instituto Tecnológico do Gás',
            ],

            [
                'initials' => 'JF',
                'meaning'  => 'Juntas de Freguesia',
            ],

            [
                'initials' => 'LEPP',
                'meaning'  => 'Local Estratégico de Pré-posicionamento',
            ],

            [
                'initials' => 'LNEC',
                'meaning'  => 'Laboratório Nacional de Engenharia Civil',
            ],

            [
                'initials' => 'MAA',
                'meaning'  => 'Monitorização Aérea Armada',
            ],

            [
                'initials' => 'MAI',
                'meaning'  => 'Ministra da Administração Interna/Ministério da Administração Interna',
            ],

            [
                'initials' => 'MARAC',
                'meaning'  => 'Meio aéreo de reconhecimento, avaliação e coordenação',
            ],

            [
                'initials' => 'MN',
                'meaning'  => 'Matas Nacionais',
            ],

            [
                'initials' => 'MR',
                'meaning'  => 'Máquina de Rasto',
            ],

            [
                'initials' => 'MRCC',
                'meaning'  => 'Maritime Rescue Coordenation Centre',
            ],

            [
                'initials' => 'NEP',
                'meaning'  => 'Norma de Execução Permanente',
            ],

            [
                'initials' => 'NOP',
                'meaning'  => 'Norma Operacional Permanente',
            ],

            [
                'initials' => 'OB',
                'meaning'  => 'Organização de Baldios',
            ],

            [
                'initials' => 'OCS',
                'meaning'  => 'Órgãos de Comunicação Social',
            ],

            [
                'initials' => 'OPAR',
                'meaning'  => 'Oficial de Operações Aéreas',
            ],

            [
                'initials' => 'OPF',
                'meaning'  => 'Organização de Produtores Florestais',
            ],

            [
                'initials' => 'PCO',
                'meaning'  => 'Posto de Comando Operacional',
            ],

            [
                'initials' => 'PCOC',
                'meaning'  => 'Posto de Comando Operacional Conjunto',
            ],

            [
                'initials' => 'PDEPC',
                'meaning'  => 'Plano Distrital de Emergência de Proteção Civil',
            ],

            [
                'initials' => 'PJ',
                'meaning'  => 'Polícia Judiciária',
            ],

            [
                'initials' => 'PLACOM',
                'meaning'  => 'Plano de Comunicações',
            ],

            [
                'initials' => 'PLANOP',
                'meaning'  => 'Plano de Operações',
            ],

            [
                'initials' => 'PMA',
                'meaning'  => 'Posto Médico Avançado',
            ],

            [
                'initials' => 'PMEPC',
                'meaning'  => 'Plano Municipal de Emergência de Proteção Civil',
            ],

            [
                'initials' => 'PNDFCI',
                'meaning'  => 'Plano Nacional de Defesa da Floresta Contra Incêndios',
            ],

            [
                'initials' => 'PNEPC',
                'meaning'  => 'Plano Nacional de Emergência de Proteção Civil',
            ],

            [
                'initials' => 'PNPG',
                'meaning'  => 'Parque Nacional da Peneda-Gerês',
            ],

            [
                'initials' => 'POM',
                'meaning'  => 'Plano Operacional Municipal',
            ],

            [
                'initials' => 'POSIT',
                'meaning'  => 'Ponto de Situação',
            ],

            [
                'initials' => 'PSP',
                'meaning'  => 'Polícia de Segurança Pública',
            ],

            [
                'initials' => 'PT',
                'meaning'  => 'Ponto de Trânsito',
            ],

            [
                'initials' => 'RCDM',
                'meaning'  => 'Relatório de Controlo Diário de Missão',
            ],

            [
                'initials' => 'REFER',
                'meaning'  => 'Rede Ferroviária Nacional',
            ],

            [
                'initials' => 'REN',
                'meaning'  => 'Rede Eléctrica Nacional',
            ],

            [
                'initials' => 'RNAP',
                'meaning'  => 'Rede Nacional de Áreas Protegidas',
            ],

            [
                'initials' => 'RNPV',
                'meaning'  => 'Rede Nacional de Postos de Vigia',
            ],

            [
                'initials' => 'ROB',
                'meaning'  => 'Rede Operacional dos Bombeiros',
            ],

            [
                'initials' => 'RPAP',
                'meaning'  => 'Relatório Preliminar sobre Acidentes Pessoais',
            ],

            [
                'initials' => 'RPAV',
                'meaning'  => 'Relatório Preliminar sobre Acidentes com Veículos',
            ],

            [
                'initials' => 'RSB',
                'meaning'  => 'Regimento de Sapadores Bombeiros',
            ],

            [
                'initials' => 'SADO',
                'meaning'  => 'Sistema de Apoio à Decisão Operacional',
            ],

            [
                'initials' => 'SDFCI',
                'meaning'  => 'Sistema de Defesa da Floresta Contra Incêndios',
            ],

            [
                'initials' => 'SEAI',
                'meaning'  => 'Secretário de Estado da Administração Interna',
            ],

            [
                'initials' => 'SEPC',
                'meaning'  => 'Secretário de estado da Protecção Civil',
            ],

            [
                'initials' => 'SEPNA',
                'meaning'  => 'Serviço de Protecção da Natureza e do Ambiente',
            ],

            [
                'initials' => 'SF',
                'meaning'  => 'Sapadores Florestais',
            ],

            [
                'initials' => 'SGO',
                'meaning'  => 'Sistema de Gestão de Operações',
            ],

            [
                'initials' => 'SGSSI',
                'meaning'  => 'Secretária-Geral do Sistema de Segurança Interna',
            ],

            [
                'initials' => 'SIOPS',
                'meaning'  => 'Sistema Integrado de operações de Protecção e Socorro',
            ],

            [
                'initials' => 'SIRESP',
                'meaning'  => 'Sistema Integrado de Redes de Emergência e Segurança de Portugal',
            ],

            [
                'initials' => 'SMPC',
                'meaning'  => 'Serviço Municipal de Proteção Civil',
            ],

            [
                'initials' => 'SMS',
                'meaning'  => 'Short Message Service',
            ],

            [
                'initials' => 'SNBS',
                'meaning'  => 'Sistemas Nacionais de Busca e Salvamento',
            ],

            [
                'initials' => 'SNIRH',
                'meaning'  => 'Sistema Nacional de Informação de Recursos Hídricos',
            ],

            [
                'initials' => 'TO',
                'meaning'  => 'Teatro de Operações',
            ],

            [
                'initials' => 'UE',
                'meaning'  => 'União Europeia',
            ],

            [
                'initials' => 'VALE',
                'meaning'  => 'Veículo de Apoio Logístico',
            ],

            [
                'initials' => 'VCI',
                'meaning'  => 'Veículo de Combate a Incêndios',
            ],

            [
                'initials' => 'VCOC',
                'meaning'  => 'Veículo de Comando e Comunicações',
            ],

            [
                'initials' => 'VCOT',
                'meaning'  => 'Veículo de Comando Tático',
            ],

            [
                'initials' => 'VGEO',
                'meaning'  => 'Veículo de Gestão Estratégica e Operações',
            ],

            [
                'initials' => 'VHF',
                'meaning'  => 'Very High Frequency',
            ],

            [
                'initials' => 'VOPE',
                'meaning'  => 'Veículo de Operações Específicas',
            ],

            [
                'initials' => 'VPCC',
                'meaning'  => 'Veículo de Planeamento, Comando e Comunicações',
            ],

            [
                'initials' => 'VTT',
                'meaning'  => 'Veículo Tanque Tático',
            ],

            [
                'initials' => 'VTTP',
                'meaning'  => 'Veículo de Transporte Tático de Pessoal',
            ],

            [
                'initials' => 'ZA',
                'meaning'  => 'Zona de Apoio',
            ],

            [
                'initials' => 'ZCR',
                'meaning'  => 'Zona de Concentração e Reserva',
            ],

            [
                'initials' => 'ZI',
                'meaning'  => 'Zona de Intervenção',
            ],

            [
                'initials' => 'ZRR',
                'meaning'  => 'Zona de Receção de Reforços',
            ],

            [
                'initials' => 'ZS',
                'meaning'  => 'Zona de Sinistro',
            ],
        ];

        foreach ($acronyms as $acronym) {
            factory(Acronym::class)->create($acronym);
        }
    }
}
