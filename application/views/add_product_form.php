<h2 class="mt-4">Adicionar Produto</h2>

<?= form_open('loja/add_product', ['class' => 'mt-3', 'id' => 'produto-form']); ?>
<div class="card p-4">
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" class="form-control" value="<?= set_value('nome'); ?>"
            placeholder="Digite o nome do produto" required>
    </div>

    <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" class="form-control" rows="3"
            placeholder="Descrição detalhada do produto"><?= set_value('descricao'); ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="preco">Preço:</label>
            <input type="number" name="preco" id="preco" class="form-control" value="<?= set_value('preco'); ?>"
                placeholder="Preço do produto" min="0" step="0.01" required>
        </div>
        <div class="form-group col-md-4">
            <label for="estoque">Estoque:</label>
            <input type="number" name="estoque" class="form-control" value="<?= set_value('estoque'); ?>"
                placeholder="Quantidade em estoque" min="0" required>
        </div>
        <div class="form-group col-md-4">
            <label for="custo">Custo:</label>
            <input type="number" name="custo" id="custo" class="form-control" value="<?= set_value('custo'); ?>"
                placeholder="Custo do produto" min="0" step="0.01" required>
        </div>
    </div>

    <!-- Exibição do lucro esperado -->
    <div class="form-group">
        <label>Lucro Esperado:</label>
        <p id="lucroEsperado" class="font-weight-bold text-success">R$ 0,00</p>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary mr-2" id="salvarBtn">Salvar</button>
        <a href="<?= base_url('loja'); ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
<?= form_close(); ?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const precoInput = document.getElementById("preco");
        const custoInput = document.getElementById("custo");
        const lucroDisplay = document.getElementById("lucroEsperado");
        const salvarBtn = document.getElementById("salvarBtn");
        const form = document.getElementById("produto-form");

        function calcularLucro() {
            let preco = parseFloat(precoInput.value) || 0;
            let custo = parseFloat(custoInput.value) || 0;
            let lucro = preco - custo;

            lucroDisplay.textContent = `R$ ${lucro.toFixed(2).replace('.', ',')}`;
            lucroDisplay.style.color = lucro >= 0 ? "green" : "red";

            
            if (custo > preco) {
                salvarBtn.disabled = true;
            } else {
                salvarBtn.disabled = false;
            }
        }

        precoInput.addEventListener("input", calcularLucro);
        custoInput.addEventListener("input", calcularLucro);

        
        form.addEventListener("submit", function(event) {
            let preco = parseFloat(precoInput.value) || 0;
            let custo = parseFloat(custoInput.value) || 0;

            if (custo > preco) {
                event.preventDefault();
                alert("Erro: O custo do produto não pode ser maior que o preço de venda!");
            }
        });
    });
</script>
