<?
class ValidaInfo {
    private $string;
    private $validacao;

    public function __construct($string, $validacao) {
        $this->string = $string;
        $this->validacao = $validacao;
    }

    public function cpf() {
        $cpf = preg_replace('/[^0-9]/', '', $this->string);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1+$/', $cpf)) {
            return false;
        }

        for ($i = 9; $i < 11; $i++) {
            $sum = 0;
            for ($j = 0; $j < $i; $j++) {
                $sum += $cpf[$j] * (($i + 1) - $j);
            }
            $remainder = $sum % 11;
            if ($remainder < 2) {
                $digit = 0;
            } else {
                $digit = 11 - $remainder;
            }
            if ($digit != $cpf[$i]) {
                return false;
            }
        }
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }

    public function cnpj() {
        $cnpj = preg_replace('/[^0-9]/', '', $this->string);

        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1+$/', $cnpj)) {
            return false;
        }

        // Validação do primeiro dígito verificador
        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;
        if ($digit1 != $cnpj[12]) {
            return false;
        }

        // Validação do segundo dígito verificador
        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;
        if ($digit2 != $cnpj[13]) {
            return false;
        }

        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }

    public function email() {
        if (filter_var($this->string, FILTER_VALIDATE_EMAIL)) {
            return $this->string;
        }
        return false;
    }

    public function telefone() {
        $telefone = preg_replace('/[^0-9]/', '', $this->string);

        if (preg_match('/^\d{10,11}$/', $telefone)) {
            $telefoneFormatado = '(' . substr($telefone, 0, 2) . ')' . substr($telefone, 2, -4) . '-' . substr($telefone, -4);
            return $telefoneFormatado;
        }
        return false;
    }

    public function usuario() {
        if (preg_match('/^[a-zA-Z0-9]{8,}$/', $this->string) && strlen($this->string) > 7 && strlen($this->string) < 20 && !preg_match('/^\d+$/', $this->string)) {
            return $this->string;
        }
        return false;
    }

    public function cep() {
        $cepRegex = '/^\d{5}-?\d{3}$/';
        if (preg_match($cepRegex, $this->string)) {
            return $this->string;
        }
        return false;
    }

    public function valida() {
        switch ($this->validacao) {
            case 'cpf':
                return $this->cpf();
            case 'cnpj':
                return $this->cnpj();
            case 'email':
                return $this->email();
            case 'telefone':
                return $this->telefone();
            case 'usuario':
                return $this->usuario();
            case 'cep':
                return $this->cep();
            default:
                return false;
        }
    }
}

?>